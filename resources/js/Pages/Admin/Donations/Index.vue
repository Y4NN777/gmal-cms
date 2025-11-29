<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { router } from '@inertiajs/vue3';
import { ref } from 'vue';
import { useI18n } from 'vue-i18n';

const { t } = useI18n();

const props = defineProps({
  donations: Object,
  stats: Object,
  filters: Object,
});

const items = props.donations?.data ?? [];
const searchQuery = ref(props.filters?.search || '');

const selectedDonation = ref(null);
const showModal = ref(false);

const formatCurrency = (amount, currency = 'USD') => {
  return new Intl.NumberFormat('en-US', { style: 'currency', currency }).format(amount || 0);
};

const formatDate = (date) => {
  if (!date) return '';
  return new Date(date).toLocaleString();
};

const getStatusColor = (status) => {
  const colors = {
    completed: 'bg-green-100 text-green-800',
    pending: 'bg-yellow-100 text-yellow-800',
    failed: 'bg-red-100 text-red-800',
  };
  return colors[status] || 'bg-gray-100 text-gray-800';
};

const translateStatus = (status) => {
  const statusMap = {
    completed: t('status.completed'),
    pending: t('status.pending'),
    failed: t('status.failed'),
  };
  return statusMap[status] || status;
};

const viewDetails = (donation) => {
  selectedDonation.value = donation;
  showModal.value = true;
};

const closeModal = () => {
  showModal.value = false;
  selectedDonation.value = null;
};

const goToLink = (link) => {
  if (!link || !link.url) return;
  router.visit(link.url, { preserveState: true, replace: true });
};

const performSearch = () => {
  router.get('/admin/donations', { 
    search: searchQuery.value
  }, {
    preserveState: true,
    preserveScroll: true,
  });
};
</script>

<template>
  <AdminLayout>
    <div class="space-y-6">
      <!-- Header -->
      <div>
        <h2 class="text-2xl font-bold text-gray-900">{{ $t('donationsManagement.title') }}</h2>
        <p class="text-gray-600 mt-1">{{ $t('donationsManagement.description') }}</p>
      </div>

      <!-- Statistics Cards -->
      <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-gradient-to-br from-green-50 to-emerald-50 rounded-lg shadow p-6 border border-green-100">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-sm font-medium text-green-600">{{ $t('donationsManagement.thisMonth') }}</p>
              <p class="text-3xl font-bold text-green-900 mt-2">{{ stats.this_month_count }}</p>
              <p class="text-xs text-green-600 mt-1">{{ $t('donationsManagement.donationsCount') }}</p>
            </div>
            <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
              <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
            </div>
          </div>
        </div>

        <div class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-lg shadow p-6 border border-blue-100">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-sm font-medium text-blue-600">{{ $t('donationsManagement.thisMonth') }}</p>
              <p class="text-3xl font-bold text-blue-900 mt-2">{{ formatCurrency(stats.this_month_amount) }}</p>
              <p class="text-xs text-blue-600 mt-1">{{ $t('donationsManagement.totalRaised') }}</p>
            </div>
            <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
              <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
            </div>
          </div>
        </div>

        <div class="bg-gradient-to-br from-purple-50 to-pink-50 rounded-lg shadow p-6 border border-purple-100">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-sm font-medium text-purple-600">{{ $t('donationsManagement.averageDonation') }}</p>
              <p class="text-3xl font-bold text-purple-900 mt-2">{{ formatCurrency(stats.average_donation) }}</p>
              <p class="text-xs text-purple-600 mt-1">{{ $t('donationsManagement.perDonation') }}</p>
            </div>
            <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
              <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
              </svg>
            </div>
          </div>
        </div>

        <div class="bg-gradient-to-br from-orange-50 to-red-50 rounded-lg shadow p-6 border border-orange-100">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-sm font-medium text-orange-600">{{ $t('donationsManagement.totalAllTime') }}</p>
              <p class="text-3xl font-bold text-orange-900 mt-2">{{ formatCurrency(stats.total_all_time) }}</p>
              <p class="text-xs text-orange-600 mt-1">{{ $t('donationsManagement.lifetimeTotal') }}</p>
            </div>
            <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center">
              <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
              </svg>
            </div>
          </div>
        </div>
      </div>

      <!-- Search Bar -->
      <div class="bg-white rounded-lg shadow p-4">
        <div class="flex gap-4">
          <div class="flex-1">
            <input
              v-model="searchQuery"
              @keyup.enter="performSearch"
              type="text"
              :placeholder="$t('donationsManagement.searchPlaceholder')"
              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent"
            />
          </div>
          <button
            @click="performSearch"
            class="px-6 py-2 bg-orange-600 text-white rounded-lg hover:bg-orange-700 transition-colors font-medium"
          >
            {{ $t('donationsManagement.search') }}
          </button>
        </div>
      </div>

      <!-- Donations Table -->
      <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
          <h3 class="text-lg font-semibold text-gray-900">{{ $t('donationsManagement.title') }}</h3>
        </div>

        <div class="overflow-x-auto">
          <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
              <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ $t('donationsManagement.donor') }}</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ $t('donationsManagement.email') }}</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ $t('donationsManagement.amount') }}</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ $t('donationsManagement.campaign') }}</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ $t('donationsManagement.status') }}</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ $t('donationsManagement.date') }}</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ $t('donationsManagement.actions') }}</th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
              <tr v-if="items.length === 0">
                <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                  <svg class="w-12 h-12 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                  </svg>
                  <p class="text-lg font-medium">{{ $t('donationsManagement.noDonationsYet') }}</p>
                  <p class="text-sm mt-1">{{ $t('donationsManagement.donationsWillAppear') }}</p>
                </td>
              </tr>
              <tr 
                v-for="donation in items" 
                :key="donation.id"
                class="hover:bg-gray-50 cursor-pointer transition-colors"
                @click="viewDetails(donation)"
              >
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="flex items-center">
                    <div>
                      <div class="text-sm font-medium text-gray-900 flex items-center gap-2">
                        {{ donation.donor_name }}
                        <span v-if="donation.is_new" class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-orange-100 text-orange-800">
                          {{ $t('common.new') }}
                        </span>
                      </div>
                      <div v-if="donation.phone" class="text-xs text-gray-500">{{ donation.phone }}</div>
                    </div>
                  </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="text-sm text-gray-900">{{ donation.email || $t('common.na') }}</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="text-sm font-semibold text-green-600">
                    {{ formatCurrency(donation.amount, donation.currency) }}
                  </div>
                  <div class="text-xs text-gray-500">{{ donation.payment_method }}</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="text-sm text-gray-900">
                    {{ donation.campaign?.title || $t('common.general') }}
                  </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full" :class="getStatusColor(donation.status)">
                    {{ translateStatus(donation.status) }}
                  </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                  {{ formatDate(donation.created_at) }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                  <button
                    @click.stop="viewDetails(donation)"
                    class="text-orange-600 hover:text-orange-900 font-medium"
                  >
                    {{ $t('common.view') }}
                  </button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>

        <!-- Pagination -->
        <div v-if="props.donations?.links?.length" class="bg-white px-6 py-4 border-t border-gray-200">
          <div class="flex items-center justify-between">
            <div class="text-sm text-gray-700">
              {{ $t('donationsManagement.showing') }} {{ props.donations.from }} {{ $t('donationsManagement.to') }} {{ props.donations.to }} {{ $t('donationsManagement.of') }} {{ props.donations.total }} {{ $t('donationsManagement.donationsCount') }}
            </div>
            <div class="flex gap-2">
              <button
                v-for="link in props.donations.links"
                :key="link.label"
                @click.prevent="goToLink(link)"
                :disabled="!link.url"
                :class="[
                  'px-3 py-2 text-sm rounded-md',
                  link.active 
                    ? 'bg-orange-600 text-white' 
                    : link.url 
                      ? 'bg-white text-gray-700 border border-gray-300 hover:bg-gray-50' 
                      : 'bg-gray-100 text-gray-400 cursor-not-allowed'
                ]"
                v-html="link.label"
              />
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Details Modal -->
    <div
      v-if="showModal"
      class="fixed inset-0 bg-black/20 backdrop-blur-sm flex items-center justify-center z-50 p-4"
      @click="closeModal"
    >
      <div
        class="bg-white rounded-lg shadow-xl max-w-2xl w-full max-h-[90vh] overflow-y-auto"
        @click.stop
      >
        <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between sticky top-0 bg-white">
          <h3 class="text-lg font-semibold text-gray-900">{{ $t('donationsManagement.donationDetails') }}</h3>
          <button
            @click="closeModal"
            class="text-gray-400 hover:text-gray-600 transition-colors"
          >
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
          </button>
        </div>

        <div v-if="selectedDonation" class="p-6 space-y-4">
          <!-- Donor Information -->
          <div>
            <h4 class="text-sm font-semibold text-gray-700 mb-3">{{ $t('donationsManagement.donorInformation') }}</h4>
            <div class="bg-gray-50 rounded-lg p-4 space-y-2">
              <div class="flex justify-between">
                <span class="text-sm text-gray-600">{{ $t('donationsManagement.name') }}:</span>
                <span class="text-sm font-medium text-gray-900">{{ selectedDonation.donor_name }}</span>
              </div>
              <div class="flex justify-between">
                <span class="text-sm text-gray-600">{{ $t('donationsManagement.email') }}:</span>
                <span class="text-sm font-medium text-gray-900">{{ selectedDonation.email || $t('common.na') }}</span>
              </div>
              <div v-if="selectedDonation.phone" class="flex justify-between">
                <span class="text-sm text-gray-600">{{ $t('donationsManagement.phone') }}:</span>
                <span class="text-sm font-medium text-gray-900">{{ selectedDonation.phone }}</span>
              </div>
            </div>
          </div>

          <!-- Payment Information -->
          <div>
            <h4 class="text-sm font-semibold text-gray-700 mb-3">{{ $t('donationsManagement.paymentInformation') }}</h4>
            <div class="bg-gray-50 rounded-lg p-4 space-y-2">
              <div class="flex justify-between">
                <span class="text-sm text-gray-600">{{ $t('donationsManagement.amount') }}:</span>
                <span class="text-sm font-bold text-green-600">{{ formatCurrency(selectedDonation.amount, selectedDonation.currency) }}</span>
              </div>
              <div class="flex justify-between">
                <span class="text-sm text-gray-600">{{ $t('donationsManagement.paymentMethod') }}:</span>
                <span class="text-sm font-medium text-gray-900">{{ selectedDonation.payment_method }}</span>
              </div>
              <div v-if="selectedDonation.payment_reference" class="flex justify-between">
                <span class="text-sm text-gray-600">{{ $t('donationsManagement.transactionId') }}:</span>
                <span class="text-sm font-mono text-gray-900 text-xs">{{ selectedDonation.payment_reference }}</span>
              </div>
              <div class="flex justify-between">
                <span class="text-sm text-gray-600">{{ $t('donationsManagement.status') }}:</span>
                <span class="px-2 py-1 text-xs font-semibold rounded-full" :class="getStatusColor(selectedDonation.status)">
                  {{ translateStatus(selectedDonation.status) }}
                </span>
              </div>
              <div class="flex justify-between">
                <span class="text-sm text-gray-600">{{ $t('donationsManagement.type') }}:</span>
                <span class="text-sm font-medium text-gray-900">{{ selectedDonation.donation_type }}</span>
              </div>
            </div>
          </div>

          <!-- Campaign Information -->
          <div v-if="selectedDonation.campaign">
            <h4 class="text-sm font-semibold text-gray-700 mb-3">{{ $t('donationsManagement.campaign') }}</h4>
            <div class="bg-gray-50 rounded-lg p-4">
              <div class="flex justify-between">
                <span class="text-sm text-gray-600">{{ $t('donationsManagement.campaignName') }}:</span>
                <span class="text-sm font-medium text-gray-900">{{ selectedDonation.campaign.title }}</span>
              </div>
            </div>
          </div>

          <!-- Additional Metadata -->
          <div v-if="selectedDonation.donor_metadata">
            <h4 class="text-sm font-semibold text-gray-700 mb-3">{{ $t('donationsManagement.additionalInformation') }}</h4>
            <div class="bg-gray-50 rounded-lg p-4">
              <pre class="text-xs text-gray-700 whitespace-pre-wrap">{{ JSON.stringify(selectedDonation.donor_metadata, null, 2) }}</pre>
            </div>
          </div>

          <!-- Date -->
          <div>
            <h4 class="text-sm font-semibold text-gray-700 mb-3">{{ $t('donationsManagement.dateTime') }}</h4>
            <div class="bg-gray-50 rounded-lg p-4">
              <div class="flex justify-between">
                <span class="text-sm text-gray-600">{{ $t('donationsManagement.received') }}:</span>
                <span class="text-sm font-medium text-gray-900">{{ formatDate(selectedDonation.created_at) }}</span>
              </div>
            </div>
          </div>
        </div>

        <div class="px-6 py-4 border-t border-gray-200 bg-gray-50 flex justify-end">
          <button
            @click="closeModal"
            class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors"
          >
            {{ $t('donationsManagement.close') }}
          </button>
        </div>
      </div>
    </div>
  </AdminLayout>
</template>
