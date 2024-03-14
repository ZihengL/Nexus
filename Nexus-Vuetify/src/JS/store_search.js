import { getAllGamesWithDeveloperName, fetchGameImages} from './fetchServices'

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
  return fetchGameImages(searchedGames)
}

export const search_AndFilter = async (
  titleOrDevName = null,
  tags = [],
  sortingValue = null
) => {
  //console.log('sorting : ', sortingValue)

  let sorting = null;

  // Only proceed if sortingValue is an object and has keys
  if (sortingValue && typeof sortingValue === 'object' && Object.keys(sortingValue).length > 0) {
  const keys = Object.keys(sortingValue)
  const key = keys[0]
  console.log('key : ', key)
  sorting = {
    [key]:sortingValue[key]
  }
}
  let games = titleOrDevName
    ? await searchOn_titleOrUsername(titleOrDevName)
    : await getAllGamesWithDeveloperName(null, null, null, sorting)

  //console.log('games  : ', games)

  if (tags.length > 0) {
    games = games.filter(game =>
      tags.every(tag => game.tags.some(gameTag => gameTag.name === tag))
    );
  }

  if (sortingValue === "devName") {
    games.sort((a, b) => {
      const nameA = a[sortingValue].toLowerCase();
      const nameB = b[sortingValue].toLowerCase();
  
      if (nameA < nameB) return -1;
      if (nameA > nameB) return 1;
      return 0;
    });
  }

  return games
}
