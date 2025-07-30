<script setup>

import GuestLayout from "../components/GuestLayout.vue";
import {ref} from "vue";
import axiosClient from "../axios.js";
import router from "../router.js";

const data = ref({
  name: '',
  email: '',
  password: '',
  password_confirmation: '',
})

const errors = ref({
  name: [],
  email: [],
  password: [],
})

function submit() {
  axiosClient.post('/register', data.value)
      .then(response => {
        console.log(response.data);
        router.push({ name: 'Login' });
      })
      .catch(error => {
        if (error.response && error.response.status === 422) {
          errors.value = error.response.data.errors;
        }
      });
}

</script>

<template>
  <GuestLayout>
    <h2 class="mt-10 text-center text-2xl font-bold text-gray-900">
      Create new Account
    </h2>

    <div class="mt-10 sm:mx-auto sm:w-full sm:max-w-sm">
      <form @submit.prevent="submit" class="space-y-4">

        <!-- Nome completo -->
        <div>
          <label for="name" class="block text-sm font-medium text-gray-900">
            Full Name
          </label>
          <input
              id="name"
              v-model="form.name"
              class="block w-full rounded-md px-3 py-1.5 outline outline-1 outline-gray-300 focus:outline-indigo-600"
          />
          <p v-if="errors.name" class="mt-1 text-sm text-red-600">
            {{ errors.name[0] }}
          </p>
        </div>

        <!-- Email -->
        <div>
          <label for="email" class="block text-sm font-medium text-gray-900">
            Email address
          </label>
          <input
              id="email"
              type="email"
              v-model="form.email"
              class="block w-full rounded-md px-3 py-1.5 outline outline-1 outline-gray-300 focus:outline-indigo-600"
          />
          <p v-if="errors.email" class="mt-1 text-sm text-red-600">
            {{ errors.email[0] }}
          </p>
        </div>

        <!-- Senha -->
        <div>
          <label for="password" class="block text-sm font-medium text-gray-900">
            Password
          </label>
          <input
              id="password"
              type="password"
              v-model="form.password"
              class="block w-full rounded-md px-3 py-1.5 outline outline-1 outline-gray-300 focus:outline-indigo-600"
          />
          <p v-if="errors.password" class="mt-1 text-sm text-red-600">
            {{ errors.password[0] }}
          </p>
        </div>

        <!-- Repetir senha -->
        <div>
          <label for="passwordConfirmation" class="block text-sm font-medium text-gray-900">
            Repeat Password
          </label>
          <input
              id="passwordConfirmation"
              type="password"
              v-model="form.password_confirmation"
              class="block w-full rounded-md px-3 py-1.5 outline outline-1 outline-gray-300 focus:outline-indigo-600"
          />
        </div>

        <div>
          <button
              type="submit"
              class="w-full rounded-md bg-indigo-600 px-3 py-1.5 text-white hover:bg-indigo-500"
          >
            Create an Account
          </button>
        </div>
      </form>

      <p class="mt-10 text-center text-sm text-gray-500">
        Already have an account?
        <RouterLink to="{ name: 'Login' }" class="font-semibold text-indigo-600 hover:text-indigo-500">
          Login here
        </RouterLink>
      </p>
    </div>
  </GuestLayout>
</template>

<style scoped>

</style>