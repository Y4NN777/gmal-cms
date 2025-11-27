<template>
  <AdminLayout :user="user">
    <div class="max-w-7xl mx-auto">
          <!-- Header -->
          <div class="mb-8 flex justify-between items-center">
            <div>
              <h2 class="text-2xl font-bold text-gray-900">Testimonials Management</h2>
              <p class="text-gray-600 mt-1">Review and approve testimonials from the community</p>
            </div>
            
            <!-- Stats -->
            <div class="flex gap-4">
              <div class="bg-yellow-50 px-4 py-2 rounded-lg">
                <div class="text-2xl font-bold text-yellow-800">{{ pendingCount }}</div>
                <div class="text-xs text-yellow-600">Pending</div>
              </div>
              <div class="bg-green-50 px-4 py-2 rounded-lg">
                <div class="text-2xl font-bold text-green-800">{{ approvedCount }}</div>
                <div class="text-xs text-green-600">Approved</div>
              </div>
            </div>
          </div>

          <!-- Filters -->
          <div class="bg-white rounded-lg shadow p-4 mb-6">
            <div class="flex gap-4">
              <button
                @click="filters.status = 'pending'"
                class="px-4 py-2 rounded-lg font-medium transition-colors"
                :class="filters.status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-700 hover:bg-gray-200'"
              >
                Pending ({{ pendingCount }})
              </button>
              <button
                @click="filters.status = 'approved'"
                class="px-4 py-2 rounded-lg font-medium transition-colors"
                :class="filters.status === 'approved' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-700 hover:bg-gray-200'"
              >
                Approved ({{ approvedCount }})
              </button>
              <button
                @click="filters.status = 'rejected'"
                class="px-4 py-2 rounded-lg font-medium transition-colors"
                :class="filters.status === 'rejected' ? 'bg-red-100 text-red-800' : 'bg-gray-100 text-gray-700 hover:bg-gray-200'"
              >
                Rejected
              </button>
              <button
                @click="filters.status = ''"
                class="px-4 py-2 rounded-lg font-medium transition-colors"
                :class="filters.status === '' ? 'bg-gray-800 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200'"
              >
                All
              </button>
            </div>
          </div>

          <!-- Loading -->
          <div v-if="loading" class="text-center py-12">
            <div class="inline-block animate-spin rounded-full h-12 w-12 border-b-2 border-orange-600"></div>
          </div>

          <!-- Testimonials Grid -->
          <div v-else-if="filteredTestimonials.length > 0" class="grid gap-6">
            <div
              v-for="testimonial in filteredTestimonials"
              :key="testimonial.id"
              class="bg-white rounded-lg shadow-lg p-6"
            >
              <div class="flex justify-between items-start mb-4">
                <div class="flex items-start gap-4">
                  <div class="w-12 h-12 bg-gradient-to-br from-orange-400 to-orange-600 rounded-full flex items-center justify-center text-white font-bold text-xl">
                    {{ testimonial.name?.charAt(0).toUpperCase() || '?' }}
                  </div>
                  <div>
                    <h3 class="font-bold text-gray-900">{{ testimonial.name }}</h3>
                    <p v-if="testimonial.position" class="text-sm text-gray-600">{{ testimonial.position }}</p>
                    <p v-if="testimonial.organization" class="text-xs text-gray-500">{{ testimonial.organization }}</p>
                  </div>
                </div>
                
                <span
                  class="px-3 py-1 text-sm font-medium rounded-full"
                  :class="{
                    'bg-yellow-100 text-yellow-800': testimonial.status === 'pending',
                    'bg-green-100 text-green-800': testimonial.status === 'approved',
                    'bg-red-100 text-red-800': testimonial.status === 'rejected',
                  }"
                >
                  {{ testimonial.status }}
                </span>
              </div>

              <!-- Content -->
              <div class="mb-4">
                <p class="text-gray-700 leading-relaxed">{{ testimonial.content }}</p>
              </div>

              <!-- Rating -->
              <div class="flex items-center gap-1 mb-4">
                <svg
                  v-for="star in 5"
                  :key="star"
                  class="w-5 h-5"
                  :class="star <= testimonial.rating ? 'text-yellow-400' : 'text-gray-300'"
                  fill="currentColor"
                  viewBox="0 0 20 20"
                >
                  <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                </svg>
                <span class="ml-2 text-sm text-gray-600">({{ testimonial.rating }}/5)</span>
              </div>

              <!-- Metadata -->
              <div class="text-xs text-gray-500 mb-4">
                Submitted {{ formatDate(testimonial.created_at) }}
                <span v-if="testimonial.approved_at"> • Approved {{ formatDate(testimonial.approved_at) }}</span>
              </div>

              <!-- Actions -->
              <div class="flex gap-3">
                <button
                  v-if="testimonial.status === 'pending'"
                  @click="approveTestimonial(testimonial.id)"
                  :disabled="actionLoading[testimonial.id]"
                  class="flex-1 px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 font-medium transition-colors disabled:opacity-50"
                >
                  ✓ Approve
                </button>
                
                <button
                  v-if="testimonial.status === 'pending'"
                  @click="rejectTestimonial(testimonial.id)"
                  :disabled="actionLoading[testimonial.id]"
                  class="flex-1 px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 font-medium transition-colors disabled:opacity-50"
                >
                  ✗ Reject
                </button>
                
                <button
                  v-if="testimonial.is_featured"
                  class="px-4 py-2 bg-yellow-100 text-yellow-800 rounded-lg font-medium"
                  disabled
                >
                  ★ Featured
                </button>
                
                <button
                  @click="deleteTestimonial(testimonial.id)"
                  :disabled="actionLoading[testimonial.id]"
                  class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 font-medium transition-colors disabled:opacity-50"
                >
                  Delete
                </button>
              </div>
            </div>
          </div>

          <!-- Empty State -->
          <div v-else class="bg-white rounded-lg shadow-lg p-12 text-center">
            <svg class="w-16 h-16 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z" />
            </svg>
            <p class="text-gray-600">No testimonials found</p>
          </div>
        </div>

    <!-- Approve Confirmation Modal -->
    <div v-if="showApproveModal" class="fixed inset-0 bg-white/30 backdrop-blur-sm flex items-center justify-center z-50" @click="showApproveModal = false">
      <div class="bg-white rounded-lg shadow-2xl p-6 max-w-md w-full mx-4" @click.stop>
        <div class="flex items-center gap-3 mb-4">
          <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
            </svg>
          </div>
          <h3 class="text-xl font-bold text-gray-900">Approve Testimonial</h3>
        </div>
        
        <p class="text-gray-600 mb-6">
          Are you sure you want to approve this testimonial? It will be visible on the public website.
        </p>
        
        <div class="flex gap-3">
          <button
            @click="showApproveModal = false"
            class="flex-1 px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 font-medium"
          >
            Cancel
          </button>
          <button
            @click="confirmApprove"
            class="flex-1 px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 font-medium"
          >
            Approve
          </button>
        </div>
      </div>
    </div>

    <!-- Reject Confirmation Modal -->
    <div v-if="showRejectModal" class="fixed inset-0 bg-white/30 backdrop-blur-sm flex items-center justify-center z-50" @click="showRejectModal = false">
      <div class="bg-white rounded-lg shadow-2xl p-6 max-w-md w-full mx-4" @click.stop>
        <div class="flex items-center gap-3 mb-4">
          <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center">
            <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
          </div>
          <h3 class="text-xl font-bold text-gray-900">Reject Testimonial</h3>
        </div>
        
        <p class="text-gray-600 mb-4">
          Are you sure you want to reject this testimonial?
        </p>
        
        <div class="mb-6">
          <label class="block text-sm font-medium text-gray-700 mb-2">
            Reason for rejection (optional)
          </label>
          <textarea
            v-model="rejectReason"
            rows="3"
            placeholder="Provide a reason..."
            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent"
          ></textarea>
        </div>
        
        <div class="flex gap-3">
          <button
            @click="showRejectModal = false"
            class="flex-1 px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 font-medium"
          >
            Cancel
          </button>
          <button
            @click="confirmReject"
            class="flex-1 px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 font-medium"
          >
            Reject
          </button>
        </div>
      </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div v-if="showDeleteModal" class="fixed inset-0 bg-white/30 backdrop-blur-sm flex items-center justify-center z-50" @click="showDeleteModal = false">
      <div class="bg-white rounded-lg shadow-2xl p-6 max-w-md w-full mx-4" @click.stop>
        <div class="flex items-center gap-3 mb-4">
          <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center">
            <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
            </svg>
          </div>
          <h3 class="text-xl font-bold text-gray-900">Delete Testimonial</h3>
        </div>
        
        <p class="text-gray-600 mb-6">
          Are you sure you want to permanently delete this testimonial? This action cannot be undone.
        </p>
        
        <div class="flex gap-3">
          <button
            @click="showDeleteModal = false"
            class="flex-1 px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 font-medium"
          >
            Cancel
          </button>
          <button
            @click="confirmDelete"
            class="flex-1 px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 font-medium"
          >
            Delete
          </button>
        </div>
      </div>
    </div>
  </AdminLayout>
</template>

<script setup>
import { ref, reactive, computed, onMounted } from 'vue';
import { router } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';

const user = ref(null);
const testimonials = ref([]);
const loading = ref(true);
const actionLoading = reactive({});

const showApproveModal = ref(false);
const showRejectModal = ref(false);
const showDeleteModal = ref(false);
const selectedTestimonialId = ref(null);
const rejectReason = ref('');

const filters = reactive({
  status: 'pending',
});

const filteredTestimonials = computed(() => {
  if (!filters.status) return testimonials.value;
  return testimonials.value.filter(t => t.status === filters.status);
});

const pendingCount = computed(() => testimonials.value.filter(t => t.status === 'pending').length);
const approvedCount = computed(() => testimonials.value.filter(t => t.status === 'approved').length);

onMounted(async () => {
  const token = localStorage.getItem('token');
  const userData = localStorage.getItem('user');
  
  if (!token || !userData) {
    router.visit('/admin/login');
    return;
  }
  
  user.value = JSON.parse(userData);
  await loadTestimonials();
});

const loadTestimonials = async () => {
  loading.value = true;
  const token = localStorage.getItem('token');
  
  try {
    const response = await fetch('/api/v1/testimonials?per_page=100', {
      headers: {
        'Authorization': `Bearer ${token}`,
        'Accept': 'application/json',
      },
    });
    
    if (!response.ok) throw new Error('Failed to load testimonials');
    
    const data = await response.json();
    testimonials.value = data.data;
  } catch (error) {
    console.error('Failed to load testimonials:', error);
  } finally {
    loading.value = false;
  }
};

const approveTestimonial = (id) => {
  selectedTestimonialId.value = id;
  showApproveModal.value = true;
};

const confirmApprove = async () => {
  const id = selectedTestimonialId.value;
  actionLoading[id] = true;
  showApproveModal.value = false;
  const token = localStorage.getItem('token');
  
  try {
    const response = await fetch(`/admin/testimonials/${id}/approve`, {
      method: 'POST',
      headers: {
        'Authorization': `Bearer ${token}`,
        'Accept': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
      },
    });
    
    if (response.ok) {
      await loadTestimonials();
    }
  } catch (error) {
    console.error('Failed to approve testimonial:', error);
    alert('Failed to approve testimonial');
  } finally {
    actionLoading[id] = false;
    selectedTestimonialId.value = null;
  }
};

const rejectTestimonial = (id) => {
  selectedTestimonialId.value = id;
  rejectReason.value = '';
  showRejectModal.value = true;
};

const confirmReject = async () => {
  const id = selectedTestimonialId.value;
  actionLoading[id] = true;
  showRejectModal.value = false;
  const token = localStorage.getItem('token');
  
  try {
    const response = await fetch(`/admin/testimonials/${id}/reject`, {
      method: 'POST',
      headers: {
        'Authorization': `Bearer ${token}`,
        'Accept': 'application/json',
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
      },
      body: JSON.stringify({
        reason: rejectReason.value,
      }),
    });
    
    if (response.ok) {
      await loadTestimonials();
    }
  } catch (error) {
    console.error('Failed to reject testimonial:', error);
    alert('Failed to reject testimonial');
  } finally {
    actionLoading[id] = false;
    selectedTestimonialId.value = null;
    rejectReason.value = '';
  }
};

const deleteTestimonial = (id) => {
  selectedTestimonialId.value = id;
  showDeleteModal.value = true;
};

const confirmDelete = async () => {
  const id = selectedTestimonialId.value;
  actionLoading[id] = true;
  showDeleteModal.value = false;
  const token = localStorage.getItem('token');
  
  try {
    const response = await fetch(`/admin/testimonials/${id}`, {
      method: 'DELETE',
      headers: {
        'Authorization': `Bearer ${token}`,
        'Accept': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
      },
    });
    
    if (response.ok) {
      await loadTestimonials();
    }
  } catch (error) {
    console.error('Failed to delete testimonial:', error);
    alert('Failed to delete testimonial');
  } finally {
    actionLoading[id] = false;
    selectedTestimonialId.value = null;
  }
};

const formatDate = (date) => {
  if (!date) return 'N/A';
  return new Date(date).toLocaleDateString('en-US', {
    year: 'numeric',
    month: 'short',
    day: 'numeric',
  });
};

const logout = async () => {
  const token = localStorage.getItem('token');
  
  try {
    await fetch('/api/v1/auth/logout', {
      method: 'POST',
      headers: {
        'Authorization': `Bearer ${token}`,
        'Accept': 'application/json',
      },
    });
  } catch (error) {
    console.error('Logout error:', error);
  }
  
  localStorage.removeItem('token');
  localStorage.removeItem('user');
  router.visit('/admin/login');
};
</script>
