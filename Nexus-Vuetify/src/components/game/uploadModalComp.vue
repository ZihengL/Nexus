<template>
  <div v-if="isVisible" class="modal-overlay">
    <div class="modal-content">
      <h3>Progrès de Téléchargement</h3>
      <div v-for="(progress, fileName) in uploadProgress" :key="fileName">
        {{ fileName }}: {{ progress }}%
      </div>
      <btnComp :contenu="'Fermer'" @toggle-btn="hideModal"></btnComp>
    </div>
  </div>
</template>

<script>
import btnComp from "../btnComponent.vue";
export default {
  components: {
    btnComp,
  },
  props: {
    isVisible: Boolean,
    uploadProgress: Object,
  },
  methods: {
    hideModal() {
      this.$emit("update:isVisible", false);
    },
  },
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
}

.modal-content {
  background: white;
  padding: 20%;
  border-radius: 5%;
}
</style>
