<template>
  <div v-if="ArrayPag.length > 0" class="pagination">
    <div>
      <a :class="isFirst ? 'first' : 'regular glow glass2'" class="roundBorderSmall" href="#" @click="previousPage()">
        < </a>
    </div>
    <div v-for="pageNb, index in ArrayPag" :key="index" class="liste" id="group1">
      <input type="radio" :id="pageNb" name="group1" :checked="pageNb === nbPageCheck" @click="giveNb(pageNb + 1)">
      <label class="glow roundBorderSmall" :for="pageNb">{{ pageNb + 1 }}</label>
    </div>
    <div>
      <a :class="isLast ? 'first' : 'regular glow glass2'"  class="roundBorderSmall"  href="#" @click="nextPage()">
        >
      </a>
    </div>
  </div>
</template>
<script setup>
  import PaginationManager from "@/JS/pagination";
  import { defineProps, defineEmits, ref } from "vue";

  const props = defineProps({
    nbPageProps: {
      type: Number,
      default: 1,
    },
    type: {
      type: String,
      default: "1",
    },
  });

  const emit = defineEmits(['nbPage'])

  let leNbPage = 1;
  let nbPageCheck = 0;
  let ArrayPag = [];

  let isFirst = ref(true);
  let isLast = ref(false);

  const giveNb = (id) => {
    leNbPage = id;
    nbPageCheck = leNbPage - 1;
    const radioInputs = document.querySelectorAll('input[name="group1"]');
    radioInputs.forEach((input, index) => {
      input.checked = index === nbPageCheck ;
      });
    console.log('le nb pa : ', leNbPage);
    console.log('le max p ', ArrayPag.length)
    PaginationManager.setStorePage(leNbPage);

    if (leNbPage === 1) {
      isFirst.value = true;
      isLast.value = false;
    } else if (leNbPage === ArrayPag.length) {
      isFirst.value = false;
      isLast.value = true;
    } else {
      isFirst.value = false;
      isLast.value = false;
    }
  
    //PaginationManager.getStorePage();

    emit("nbPage")
  }

  for (let index = 0; index < props.nbPageProps; index++) {
    ArrayPag.push(index);
  }

  const nextPage = () => {
    if (leNbPage < ArrayPag.length) {
      leNbPage++;
      //console.log('le nb + : ', leNbPage);
      giveNb(leNbPage)

    }
  }

  const previousPage = () => {
    if (leNbPage > 1) {
      leNbPage--;
      //console.log('le nb - : ', leNbPage);
      giveNb(leNbPage)
    }
  }
</script>

<style lang="scss">
.pagination {
  display: flex;
  justify-content: center;
  margin-top: 10%;
  padding-bottom: 5%;

  .first {
    color: rgba(255, 255, 255, 0.223);
  }
  .first:hover {
    color: rgba(255, 255, 255, 0.223);
  }

  .liste {
    display: flex;
    flex-direction: column;
    margin: 0% 1%;
    gap: 0%;
  }

  input {
    display: none;
  }

  input:checked+label {
    color: var(--light);
    /* From https://css.glass */
    background: rgba(255, 255, 255, 0.250);
    text-shadow:
      0 0 1.5px #ffffffa1,
      0 0 2.5px #ffffffa1,
      0 0 5px #ffffffa1,
      0 0 10px var(--marin-b),
      0 0 20px var(--marin-b),
      0 0 25px var(--marin-b),
      0 0 30px var(--marin-b),
      0 0 33px var(--marin-b);
    animation: neonGlow 0.5s ease-in-out infinite alternate;
  }

  label,
  a {
    color: var(--light-trans-2);
    padding: 8px 16px;
    text-decoration: none;
    transition: background-color .3s;
    /* From https://css.glass */
    background: rgba(255, 255, 255, 0.06);
    //border-radius: 0px;
    box-shadow: 0 4px 30px rgba(0, 0, 0, 0.1);
    backdrop-filter: blur(0px);
    -webkit-backdrop-filter: blur(0px);
  }

  a {
    font-size: 1.6rem;
    padding: 0%;
    padding: 0px 16px;
  }
}
</style>
