<template>
  <div v-if="ArrayPag.length > 0" class="pagination">
    <div><a  class="glow glass2 roundBorderSmall" href="#"><</a></div>
    <div v-for="pageNb, index in ArrayPag" :key="index" class="liste"  id="group1">
      <input type="radio" :id="pageNb" name="group1" :checked="pageNb === 0"  @click="giveNb(pageNb)">
      <label  class="glow roundBorderSmall" :for="pageNb">{{ pageNb + 1 }}</label>
    </div>
    <div><a class="glow glass2 roundBorderSmall" href="#">></a></div>
  </div>
</template>


<script setup>
  import { defineProps, ref} from "vue";
  import PaginationManager from "@/JS/pagination";

  const props = defineProps(["nbPageProps"]);
  const emit = defineEmits(['nbPage'])
  let leNbPage = 1;
  const giveNb = (id) => {
    leNbPage = id + 1;
    PaginationManager.setPage(leNbPage);
    emit("nbPage")
  }


  let ArrayPag = [];
 // console.log('nb page : ',props.nbPageProps)
  for (let index = 0; index < props.nbPageProps; index++) {
    ArrayPag.push(index);
  }
 // console.log('array page : ',ArrayPag)

</script>

<style lang="scss">
.pagination {
  display: flex;
  justify-content: center;
  div {
    padding: 0%;
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
  input:checked + label, label:hover, a:hover {
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
  label, a {
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
