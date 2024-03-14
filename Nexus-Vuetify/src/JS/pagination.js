const PaginationManager = {
    setStorePage (id) {
      console.log('put : ', id)
        localStorage.setItem('nbPagination', id)
    },
    getStorePage () {
      let data = localStorage.getItem('nbPagination');
      console.log('data get : ', data);
      return data;
    }
}
  
export default PaginationManager