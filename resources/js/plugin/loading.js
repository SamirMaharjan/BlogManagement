import { createVNode, render } from 'vue';
import Loader from '../components/Loader.vue';

const LoadingPlugin = {
  install(app) {
    // Create a loading state
    const state = {
      isVisible: false,
      message: 'Loading...',
      overlay: true
    };

    // Create a container for the loader
    const loaderContainer = document.createElement('div');
    loaderContainer.id = 'app-loader';
    document.body.appendChild(loaderContainer);

    // Create the loader VNode
    const vnode = createVNode(Loader, {
      isVisible: state.isVisible,
      message: state.message,
      overlay: state.overlay
    });

    // Render the loader to the container
    render(vnode, loaderContainer);

    // Create global $loading object for managing the loader
    app.config.globalProperties.$loading = {
      show(message = 'Loading...', overlay = true) {
        state.isVisible = true;
        state.message = message;
        state.overlay = overlay;
        
        // Update the loader with new props
        const updatedNode = createVNode(Loader, {
          isVisible: state.isVisible,
          message: state.message,
          overlay: state.overlay
        });
        render(updatedNode, loaderContainer);
      },
      hide() {
        state.isVisible = false;
        
        // Update the loader with new isVisible state
        const updatedNode = createVNode(Loader, {
          isVisible: state.isVisible,
          message: state.message,
          overlay: state.overlay
        });
        render(updatedNode, loaderContainer);
      },
      get isVisible() {
        return state.isVisible;
      }
    };

    // Provide loading utilities for Composition API
    app.provide('loading', {
      show: app.config.globalProperties.$loading.show,
      hide: app.config.globalProperties.$loading.hide,
      isVisible: () => state.isVisible
    });
  }
};

export default LoadingPlugin;