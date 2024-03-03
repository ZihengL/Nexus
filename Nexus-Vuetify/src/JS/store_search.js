import { getAllGamesWithDeveloperName } from './fetchServices'

export const searchOn_titleOrUsername = async titleOrDevName => {
  let games = await getAllGamesWithDeveloperName()
  //   console.log('games : ', games)
  const searchQuery = titleOrDevName.toLowerCase()
  const searchedGames = games.filter(game => {
    const titleLower = game.title ? game.title.toLowerCase() : ''
    const devNameLower = game.devName ? game.devName.toLowerCase() : ''

    return (
      titleLower.includes(searchQuery) || devNameLower.includes(searchQuery)
    )
  })
  //   console.log('searchedGames : ', searchedGames)
  return searchedGames
}

export const search_AndFilter = async (
  titleOrDevName = null,
  tags = [],
  sorting = null
) => {
  let games = titleOrDevName
    ? await searchOn_titleOrUsername(titleOrDevName)
    : await getAllGamesWithDeveloperName()

  if (tags.length > 0) {
    games = games.filter(game =>
      tags.every(tag => game.tags.some(gameTag => gameTag.name === tag))
    )
  }

  if (sorting) {
    games.sort((a, b) => {
      // Replace 'sortKey' with the actual key you want to sort by
      const sortKey = sorting
      if (a[sortKey] < b[sortKey]) return -1
      if (a[sortKey] > b[sortKey]) return 1
      return 0
    })
  }

  return games
}
