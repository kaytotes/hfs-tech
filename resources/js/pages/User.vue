<template>
<div class="container px-2 md:px-0 mx-auto space-y-2">
  <div class="md:flex md:justify-between md:space-x-2 py-6">
    <div class="md:w-1/3 pb-4">
      <h3 class="text-xl">Profile</h3>
      <p class="mt-1 text-sm text-gray-600">
        Update your account's profile information and email address.
      </p>
    </div>
    <profile-update class="md:w-2/3 p-4 bg-white shadow-md rounded-lg"></profile-update>
  </div>

  <div class="md:flex md:justify-between md:space-x-2 py-6">
    <div class="md:w-1/3 pb-4">
      <h3 class="text-xl">Password</h3>
      <p class="mt-1 text-sm text-gray-600">
        Ensure your account is using a long, random password to stay secure.
      </p>
    </div>
    <password-update class="md:w-2/3 p-4 bg-white shadow-md rounded-lg"></password-update>
  </div>
</div>
</template>

<script>
import ProfileUpdate from '@/js/components/user/ProfileUpdate'
import PasswordUpdate from '@/js/components/user/PasswordUpdate'

export default {
  components: {
    ProfileUpdate,
    PasswordUpdate,
  }
}
</script>
