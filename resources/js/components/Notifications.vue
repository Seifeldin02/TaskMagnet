<!-- resources/js/components/Notification.vue -->

<template>
    <div v-if="notifications.length > 0" class="notification-container">
      <div v-for="(notification, index) in notifications" :key="index" class="notification">
        {{ notification.message }}
        <a :href="notification.link">View Task</a>
      </div>
    </div>
  </template>
  
  <script>
  export default {
    data() {
      return {
        notifications: [],
      };
    },
    mounted() {
      // Fetch notifications from the server or use Livewire or other methods to get notifications
      // For simplicity, let's assume notifications are available in the 'window' object
      this.notifications = window.notifications || [];
    },
  };
  </script>
  
  <style scoped>
  .notification-container {
    position: fixed;
    top: 10px;
    right: 10px;
    z-index: 1000;
  }
  
  .notification {
    background-color: #ffffff;
    border: 1px solid #ddd;
    padding: 10px;
    margin-bottom: 10px;
  }
  </style>
  