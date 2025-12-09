<template>
  <div class="p-6">
    <h3 class="text-xl font-semibold text-gray-900 mb-6">Contact Information</h3>
    
    <div class="space-y-6">
      <!-- Email -->
      <div>
        <label for="contact_email" class="block text-sm font-medium text-gray-700 mb-2">
          <span class="flex items-center gap-2">
            <svg class="w-4 h-4 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
            </svg>
            Email Address
          </span>
        </label>
        <input
          id="contact_email"
          :value="email"
          @input="$emit('update:email', $event.target.value)"
          type="email"
          class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent text-base"
          placeholder="contactus@givemealift.org"
        />
      </div>

      <!-- Phone Numbers -->
      <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">
          <span class="flex items-center gap-2">
            <svg class="w-4 h-4 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
            </svg>
            Phone Numbers
          </span>
        </label>
        <div class="space-y-2">
          <div v-for="(phone, index) in phoneNumbers" :key="index" class="flex items-center gap-2">
            <input
              :value="phone"
              @input="updatePhone(index, $event.target.value)"
              type="tel"
              class="flex-1 px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent text-base font-mono"
              :placeholder="index === 0 ? '+1 405 589 0915' : '+226 75 43 45 45'"
            />
            <button
              v-if="phoneNumbers.length > 1"
              type="button"
              @click="removePhone(index)"
              class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors"
              title="Remove"
            >
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
              </svg>
            </button>
          </div>
        </div>
        <button
          type="button"
          @click="addPhone"
          class="mt-2 px-3 py-1.5 text-sm text-orange-600 hover:bg-orange-50 rounded-lg transition-colors flex items-center gap-1"
        >
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
          </svg>
          Add Phone Number
        </button>
      </div>

      <!-- Physical Addresses -->
      <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">
          <span class="flex items-center gap-2">
            <svg class="w-4 h-4 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
            Physical Locations
          </span>
        </label>
        <div class="space-y-3">
          <div v-for="(address, index) in addresses" :key="index" class="border border-gray-200 rounded-lg p-4">
            <div class="flex items-start gap-3">
              <div class="flex-1 space-y-3">
                <!-- Country Selection with Flag -->
                <div>
                  <label class="block text-xs font-medium text-gray-600 mb-1">Country</label>
                  <select
                    :value="address.country"
                    @change="updateAddress(index, 'country', $event.target.value)"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent text-sm"
                  >
                    <option value="">Select Country</option>
                    <option value="US">🇺🇸 United States</option>
                    <option value="BF">🇧🇫 Burkina Faso</option>
                    <option value="CA">🇨🇦 Canada</option>
                    <option value="GB">🇬🇧 United Kingdom</option>
                    <option value="FR">🇫🇷 France</option>
                    <option value="DE">🇩🇪 Germany</option>
                  </select>
                </div>
                
                <!-- Full Address -->
                <div>
                  <label class="block text-xs font-medium text-gray-600 mb-1">Address Details (Optional)</label>
                  <textarea
                    :value="address.details"
                    @input="updateAddress(index, 'details', $event.target.value)"
                    rows="2"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent text-sm"
                    placeholder="Street address, city, state/province, postal code..."
                  ></textarea>
                </div>
              </div>
              
              <button
                v-if="addresses.length > 1"
                type="button"
                @click="removeAddress(index)"
                class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors"
                title="Remove"
              >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
              </button>
            </div>
          </div>
        </div>
        <button
          type="button"
          @click="addAddress"
          class="mt-2 px-3 py-1.5 text-sm text-orange-600 hover:bg-orange-50 rounded-lg transition-colors flex items-center gap-1"
        >
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
          </svg>
          Add Location
        </button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue';

const props = defineProps({
  email: {
    type: String,
    default: '',
  },
  phone: {
    type: String,
    default: '',
  },
  address: {
    type: String,
    default: '',
  },
});

const emit = defineEmits(['update:email', 'update:phone', 'update:address']);

const phoneNumbers = computed(() => {
  if (!props.phone) return [''];
  return props.phone.split('\n').filter(p => p.trim());
});

const addresses = computed(() => {
  if (!props.address) return [{ country: '', details: '' }];
  
  return props.address.split('\n').map(addr => {
    const trimmed = addr.trim();
    // Check if it's just a country name
    if (trimmed === 'United States' || trimmed === 'US') {
      return { country: 'US', details: '' };
    } else if (trimmed === 'Burkina Faso' || trimmed === 'BF') {
      return { country: 'BF', details: '' };
    } else {
      return { country: '', details: trimmed };
    }
  }).filter(a => a.country || a.details);
});

const updatePhone = (index, value) => {
  const phones = [...phoneNumbers.value];
  phones[index] = value;
  emit('update:phone', phones.filter(p => p.trim()).join('\n'));
};

const addPhone = () => {
  const phones = [...phoneNumbers.value, ''];
  emit('update:phone', phones.join('\n'));
};

const removePhone = (index) => {
  const phones = phoneNumbers.value.filter((_, i) => i !== index);
  emit('update:phone', phones.join('\n'));
};

const updateAddress = (index, field, value) => {
  const addrs = [...addresses.value];
  addrs[index][field] = value;
  
  // Convert back to string format
  const formatted = addrs
    .filter(a => a.country || a.details)
    .map(a => {
      if (a.country === 'US') return 'United States';
      if (a.country === 'BF') return 'Burkina Faso';
      if (a.country === 'CA') return 'Canada';
      if (a.country === 'GB') return 'United Kingdom';
      if (a.country === 'FR') return 'France';
      if (a.country === 'DE') return 'Germany';
      return a.details;
    })
    .join('\n');
  
  emit('update:address', formatted);
};

const addAddress = () => {
  const addrs = [...addresses.value, { country: '', details: '' }];
  const formatted = addrs
    .map(a => {
      if (a.country === 'US') return 'United States';
      if (a.country === 'BF') return 'Burkina Faso';
      if (a.country === 'CA') return 'Canada';
      if (a.country === 'GB') return 'United Kingdom';
      if (a.country === 'FR') return 'France';
      if (a.country === 'DE') return 'Germany';
      return a.details;
    })
    .filter(a => a)
    .join('\n');
  
  emit('update:address', formatted);
};

const removeAddress = (index) => {
  const addrs = addresses.value.filter((_, i) => i !== index);
  const formatted = addrs
    .map(a => {
      if (a.country === 'US') return 'United States';
      if (a.country === 'BF') return 'Burkina Faso';
      if (a.country === 'CA') return 'Canada';
      if (a.country === 'GB') return 'United Kingdom';
      if (a.country === 'FR') return 'France';
      if (a.country === 'DE') return 'Germany';
      return a.details;
    })
    .filter(a => a)
    .join('\n');
  
  emit('update:address', formatted);
};
</script>
