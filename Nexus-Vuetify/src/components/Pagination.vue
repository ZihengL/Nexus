<template>
  <div class="pagination">
    <a href="#" class="prev">&laquo; Previous</a>
    <a href="#" class="active">1</a>
    <a href="#">2</a>
    <a href="#">3</a>
    <a href="#">4</a>
    <a href="#">5</a>
    <a href="#" class="next">Next &raquo;</a>
  </div>
</template>
<style>
.pagination {
  display: inline-block;
  margin: 20px;
}

.pagination a {
  color: black;
  float: left;
  padding: 8px 16px;
  text-decoration: none;
  transition: background-color .3s;
  border: 1px solid #ddd;
}

.pagination a.active {
  background-color: #4CAF50;
  color: white;
  border: 1px solid #4CAF50;
}

.pagination a:hover:not(.active) {
  background-color: #ddd;
}

.pagination .prev,
.pagination .next {
  background-color: #f1f1f1;
  color: black;
}

@media screen and (max-width: 600px) {
  .pagination a {
    float: none;
  }

  .pagination .prev,
  .pagination .next {
    display: block;
  }
}</style>
