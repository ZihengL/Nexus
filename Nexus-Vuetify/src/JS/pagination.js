const PaginationManager = {
    setStorePage (id) {
        localStorage.setItem('nbPagination', id)
    },
    getStorePage () {
      return localStorage.getItem('nbPagination')
    }
}
  
export default PaginationManager