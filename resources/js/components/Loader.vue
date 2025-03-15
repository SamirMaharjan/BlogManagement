<template>
    <div v-if="isVisible" class="loader-container" :class="{ 'loader-overlay': overlay }">
      <div class="loader-content">
        <div class="loader-spinner"></div>
        <div v-if="message" class="loader-message">{{ message }}</div>
      </div>
    </div>
  </template>
  
  <script>
  export default {
    name: 'Loader',
    props: {
      isVisible: {
        type: Boolean,
        default: true
      },
      message: {
        type: String,
        default: 'Loading...'
      },
      overlay: {
        type: Boolean,
        default: true
      }
    }
  }
  </script>
  
  <style>
  .loader-container {
    display: flex;
    justify-content: center;
    align-items: center;
    padding: 1rem;
  }
  
  .loader-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(255, 255, 255, 0.7);
    z-index: 9999;
  }
  
  .loader-content {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    background-color: white;
    padding: 1.5rem;
    border-radius: 0.5rem;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
  }
  
  .loader-spinner {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    border: 3px solid #f3f3f3;
    border-top: 3px solid #3498db;
    animation: spin 1s linear infinite;
  }
  
  .loader-message {
    margin-top: 1rem;
    font-size: 0.875rem;
    color: #4b5563;
  }
  
  @keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
  }
  </style>