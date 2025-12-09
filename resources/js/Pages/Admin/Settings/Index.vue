<template>
  <AdminLayout>
    <Head title="Settings" />

    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
          <h2 class="text-3xl font-bold text-gray-900">Settings</h2>
          <p class="mt-2 text-sm text-gray-600">Manage your website settings, social links, and appearance</p>
        </div>

        <!-- Settings Form -->
        <form @submit.prevent="updateSettings" class="space-y-6">
          <!-- General Settings -->
          <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 border-b border-gray-200">
              <h3 class="text-xl font-semibold text-gray-900 mb-4">General Information</h3>
              
              <div class="space-y-6">
                <!-- Site Name -->
                <div>
                  <label for="site_name" class="block text-sm font-medium text-gray-700 mb-2">
                    Site Name <span class="text-red-500">*</span>
                  </label>
                  <input
                    id="site_name"
                    v-model="form.general.site_name"
                    type="text"
                    required
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent"
                    placeholder="Give Me A Lift"
                  />
                </div>

                <!-- Site Description -->
                <div>
                  <label for="site_description" class="block text-sm font-medium text-gray-700 mb-2">
                    Site Description
                  </label>
                  <textarea
                    id="site_description"
                    v-model="form.general.site_description"
                    rows="3"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent"
                    placeholder="Empowering communities through education and support"
                  ></textarea>
                </div>
              </div>
            </div>
          </div>

          <!-- Appearance Settings -->
          <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 border-b border-gray-200">
              <h3 class="text-xl font-semibold text-gray-900 mb-4">Appearance</h3>
              
              <div class="space-y-6">
                <!-- Logo Upload -->
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-2">
                    Site Logo
                  </label>
                  
                  <!-- Current Logo Preview -->
                  <div v-if="form.appearance.site_logo" class="mb-4">
                    <img 
                      :src="form.appearance.site_logo" 
                      alt="Current Logo" 
                      class="h-20 object-contain bg-gray-100 p-2 rounded"
                    />
                  </div>

                  <!-- Logo Upload Input -->
                  <div class="flex items-center gap-4">
                    <input
                      ref="logoInput"
                      type="file"
                      accept="image/*"
                      class="hidden"
                      @change="handleLogoUpload"
                    />
                    <button
                      type="button"
                      @click="$refs.logoInput.click()"
                      class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 font-medium"
                    >
                      Choose New Logo
                    </button>
                    <span class="text-sm text-gray-500">PNG, JPG, SVG (max 2MB)</span>
                  </div>
                  <p v-if="logoUploading" class="text-sm text-orange-600 mt-2">Uploading...</p>
                </div>
              </div>
            </div>
          </div>

          <!-- Contact Information -->
          <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <ContactSection
              :email="form.contact.contact_email"
              :phone="form.contact.contact_phone"
              :address="form.contact.contact_address"
              @update:email="form.contact.contact_email = $event"
              @update:phone="form.contact.contact_phone = $event"
              @update:address="form.contact.contact_address = $event"
            />
          </div>

          <!-- Social Networks -->
          <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <SocialNetworksSection
              :commonNetworks="commonNetworks"
              :commonNetworksActive="commonNetworksActive"
              :customNetworks="customNetworks"
              @update:commonNetworks="commonNetworks = $event"
              @update:commonNetworksActive="commonNetworksActive = $event"
              @addCustom="addSocialNetwork"
              @removeCustom="removeCustomNetwork"
            />
          </div>

          <!-- Submit Button -->
          <div class="flex justify-end">
            <button
              type="submit"
              :disabled="submitting"
              class="px-6 py-3 bg-gradient-to-r from-orange-500 to-orange-600 text-white rounded-lg hover:from-orange-600 hover:to-orange-700 font-semibold disabled:opacity-50"
            >
              {{ submitting ? 'Saving...' : 'Save Settings' }}
            </button>
          </div>
        </form>
      </div>
    </div>
  </AdminLayout>
</template>

<script setup>
import { ref, reactive } from 'vue';
import { Head, router } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import ContactSection from './ContactSection.vue';
import SocialNetworksSection from './SocialNetworksSection.vue';

const props = defineProps({
  settings: {
    type: Object,
    required: true,
  },
});

const form = reactive({
  general: {
    site_name: props.settings.general?.site_name || '',
    site_description: props.settings.general?.site_description || '',
  },
  contact: {
    contact_email: props.settings.contact?.contact_email || '',
    contact_phone: props.settings.contact?.contact_phone || '',
    contact_address: props.settings.contact?.contact_address || '',
  },
  appearance: {
    site_logo: props.settings.appearance?.site_logo || '',
  },
});

// Default placeholders from actual website
const defaultContactEmail = 'contactus@givemealift.org';
const defaultContactPhone = '+1 405 589 0915\n+226 75 43 45 45\n+226 67 18 24 38';
const defaultContactAddress = 'United States\nBurkina Faso';

// Parse social networks from JSON
const parseSocialNetworks = () => {
  const socialNetworksValue = props.settings.social?.social_networks;
  let networks = [];
  
  if (typeof socialNetworksValue === 'string') {
    try {
      networks = JSON.parse(socialNetworksValue);
    } catch (e) {
      networks = [];
    }
  } else if (Array.isArray(socialNetworksValue)) {
    networks = socialNetworksValue;
  }
  
  return networks;
};

const allNetworks = parseSocialNetworks();

const commonNetworks = reactive({
  facebook: allNetworks.find(n => n.platform === 'facebook')?.url || '',
  twitter: allNetworks.find(n => n.platform === 'twitter')?.url || '',
  instagram: allNetworks.find(n => n.platform === 'instagram')?.url || '',
  linkedin: allNetworks.find(n => n.platform === 'linkedin')?.url || '',
  youtube: allNetworks.find(n => n.platform === 'youtube')?.url || '',
});

const commonNetworksActive = reactive({
  facebook: allNetworks.find(n => n.platform === 'facebook')?.is_active ?? true,
  twitter: allNetworks.find(n => n.platform === 'twitter')?.is_active ?? true,
  instagram: allNetworks.find(n => n.platform === 'instagram')?.is_active ?? true,
  linkedin: allNetworks.find(n => n.platform === 'linkedin')?.is_active ?? true,
  youtube: allNetworks.find(n => n.platform === 'youtube')?.is_active ?? true,
});

const customNetworks = ref(
  allNetworks.filter(n => !['facebook', 'twitter', 'instagram', 'linkedin', 'youtube'].includes(n.platform))
    .map(n => ({ ...n, is_active: n.is_active ?? true }))
);

const submitting = ref(false);
const logoUploading = ref(false);
const logoInput = ref(null);

const addSocialNetwork = () => {
  customNetworks.value.push({
    platform: '',
    url: '',
  });
};

const removeCustomNetwork = (index) => {
  customNetworks.value.splice(index, 1);
};

const updateSettings = () => {
  submitting.value = true;

  const settingsArray = [];
  
  // Convert form data to settings array
  Object.keys(form).forEach(group => {
    Object.keys(form[group]).forEach(key => {
      settingsArray.push({
        key: key,
        value: form[group][key],
      });
    });
  });

  // Build social networks array
  const socialNetworksArray = [];
  
  // Add common networks if they have URLs
  Object.keys(commonNetworks).forEach(platform => {
    if (commonNetworks[platform]) {
      socialNetworksArray.push({
        platform: platform,
        url: commonNetworks[platform],
        is_active: commonNetworksActive[platform],
      });
    }
  });
  
  // Add custom networks
  customNetworks.value.forEach(network => {
    if (network.platform && network.url) {
      socialNetworksArray.push({
        platform: network.platform,
        url: network.url,
        is_active: network.is_active ?? true,
      });
    }
  });

  // Add social networks as JSON
  settingsArray.push({
    key: 'social_networks',
    value: JSON.stringify(socialNetworksArray),
  });

  router.post('/admin/settings', { settings: settingsArray }, {
    onFinish: () => {
      submitting.value = false;
    }
  });
};

const handleLogoUpload = (event) => {
  const file = event.target.files[0];
  if (!file) return;

  logoUploading.value = true;
  
  const formData = new FormData();
  formData.append('logo', file);

  router.post('/admin/settings/upload-logo', formData, {
    onSuccess: (page) => {
      // Update the logo preview
      if (page.props.settings?.appearance?.site_logo) {
        form.appearance.site_logo = page.props.settings.appearance.site_logo;
      }
      logoUploading.value = false;
    },
    onError: () => {
      logoUploading.value = false;
    }
  });
};
</script>
