<template>
  <div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-gray-900 to-gray-800 px-4">
    <div class="max-w-md w-full space-y-8">
      <!-- Logo & Title -->
      <div class="text-center">
        <h1 class="text-4xl font-bold text-white mb-2">GiveMeALift</h1>
        <p class="text-gray-400">Admin Panel Login</p>
      </div>

      <!-- Login Form -->
      <div class="bg-white rounded-lg shadow-xl p-8">
        <form @submit.prevent="login" class="space-y-6">
          <!-- Email -->
          <div>
            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
              Email Address
            </label>
            <input
              id="email"
              v-model="form.email"
              type="email"
              required
              autocomplete="email"
              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent"
              :class="{ 'border-red-500': form.errors.email }"
              placeholder="admin@givemealift.org"
            />
            <p v-if="form.errors.email" class="mt-1 text-sm text-red-600">{{ form.errors.email }}</p>
          </div>

          <!-- Password -->
          <div>
            <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
              Password
            </label>
            <input
              id="password"
              v-model="form.password"
              type="password"
              required
              autocomplete="current-password"
              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent"
              :class="{ 'border-red-500': form.errors.password }"
              placeholder="••••••••"
            />
            <p v-if="form.errors.password" class="mt-1 text-sm text-red-600">{{ form.errors.password }}</p>
          </div>

          <!-- Remember Me -->
          <div class="flex items-center">
            <input
              id="remember"
              v-model="form.remember_me"
              type="checkbox"
              class="h-4 w-4 text-orange-600 focus:ring-orange-500 border-gray-300 rounded"
            />
            <label for="remember" class="ml-2 block text-sm text-gray-700">
              Remember me for 30 days
            </label>
          </div>

          <!-- Error Message -->
          <div v-if="form.errors.email || form.errors.password" class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg">
            {{ form.errors.email || form.errors.password }}
          </div>

          <!-- Submit Button -->
          <button
            type="submit"
            :disabled="form.processing"
            class="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-orange-600 hover:bg-orange-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500 disabled:opacity-50 disabled:cursor-not-allowed"
          >
            <span v-if="!form.processing">Sign In</span>
            <span v-else class="flex items-center">
              <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
              </svg>
              Signing in...
            </span>
          </button>
        </form>

        <!-- Back to Home -->
        <div class="mt-6 text-center">
          <a href="/" class="text-sm text-gray-600 hover:text-orange-600">
            ← Back to Website
          </a>
        </div>
      </div>

      <!-- Footer Note -->
      <p class="text-center text-sm text-gray-500">
        This is an admin-only area. Regular users do not need accounts.
      </p>
    </div>
  </div>
</template>

<script setup>
import { useForm } from '@inertiajs/vue3';

const form = useForm({
  email: '',
  password: '',
  remember_me: false,
});

const login = () => {
  form.post('/admin/login', {
    preserveScroll: true,
  });
};
</script>
