<template>
  <div v-if="props.isVisible" class="modal-overlay">
    <div class="modal-content">
      <h3>Progrès de Téléchargement</h3>
      <div v-for="(progress, fileName) in props.uploadProgress" :key="fileName">
        {{ fileName }}: {{ progress }}%
      </div>
      <!-- <button @click="hideModal">Fermer</button> -->
    </div>
  </div>
</template>

<script setup>
const props = defineProps({
  isVisible: Boolean,
  uploadProgress: Object,
});

const emit = defineEmits(["update:isVisible"]);

const hideModal = () => {
  emit("update:isVisible", false);
};
</script>

<style>
.modal-overlay {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(0, 0, 0, 0.5);
  display: flex;
  justify-content: center;
  align-items: center;
  z-index: 1000; /* Ensure the modal is above other content */
}

.modal-content {
  background: -webkit-linear-gradient(right, var(--purple), var(--dark-blue), var(--purple), var(--dark-blue));
  font-size: 2rem;
  padding: 2rem;
  color: white;
  border-radius: 1rem;
}
</style>
