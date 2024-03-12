const PaginationManager = {
    setPage (id) {
        localStorage.setItem('nbPagination', id)
    },
    getPage () {
      return localStorage.getItem('nbPagination')
    }
}
  
export default PaginationManager