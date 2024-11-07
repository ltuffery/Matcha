// src/directives/doubleTap.js

export default {
    mounted(el, binding) {
      let lastTap = 0;
  
      // Fonction pour gérer le double tap sur mobile
      const handleTouchStart = (event) => {
        const currentTime = new Date().getTime();
        const tapLength = currentTime - lastTap;
  
        if (tapLength < 500 && tapLength > 0) {
          binding.value(event); // Exécute la fonction de double-tap
        }
        lastTap = currentTime;
      };
  
      // Fonction pour gérer le double-clic pour ordinateur
      const handleDoubleClick = (event) => {
        binding.value(event); // Exécute la fonction de double-clic
      };
  
      el.addEventListener("dblclick", handleDoubleClick); // Pour ordinateur
      el.addEventListener("touchstart", handleTouchStart); // Pour mobile
  
      // Nettoyage
      el._doubleTapCleanup = () => {
        el.removeEventListener("dblclick", handleDoubleClick);
        el.removeEventListener("touchstart", handleTouchStart);
      };
    },
    unmounted(el) {
      el._doubleTapCleanup();
    }
  };