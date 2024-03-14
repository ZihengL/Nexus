import { fetchData } from "./fetch";
import * as services from "./fetchServices/services";
import StorageManager from "./localStorageManager";
import { getStorage, ref, getDownloadURL } from "firebase/storage";
import defaultImage from '@/assets/imgJeuxLogo/noImg.jpg';
//import { forEach } from "core-js/core/array";
const storage = getStorage();

// NEXT 2 FUNCTIONS BELOW ARE FOR PARSING JOINS
// CUZ THE DATA IS CONCATENATED INTO A STRING

// SEPARATOR FOR ROWS = '|'
// SEPARATOR FOR COLUMNS = ';'
// SEPARATOR BETWEEN COLUMN NAME AND DATA = ':'
export const parseDetails = (details) => {
  const rows = details.split('|');

  return rows.map(row => {
    const columns = row.split(';');

    const object = columns.reduce((acc, curr) => {
      const [key, value] = curr.split(':');
      acc[key.trim()] = value.trim();

      return acc;
    }, {});
    
    return object;
  });
}

export const parseJoins = (result, keys) => {
  for (var key in keys) {
    const detailsKey = key + "_details";

    if (result.length > 1)
      for (var index in result) {
        const obj = result[index];

        obj[key] = parseDetails(obj[detailsKey]);
        delete obj[detailsKey];
      }
    else {
      result[key] = parseDetails(result[detailsKey]);
      delete result[detailsKey];
    }
  }

  return result;
}

export const getOne = async (table, column, value, includedColumns = null, joined_tables = null) => {
  // let data = await fetchData(table, "getOne", column, value, includedColumns, sorting, null, "GET");

  const preppedBody = services.prepGetOne(column, value, includedColumns, joined_tables);
  let result = await services.getOne(table, preppedBody);

  if (result) {
    return joined_tables ? parseJoins(result, joined_tables) : result;

  }

  return null;
};

export const getAll = async (table, column = null, value = null, includedColumns = null, sorting = null, joined_tables = null, paging = null) => {
  // let data = await fetchData(table, "getAll", column, value, includedColumns, sorting, null, "GET");

  const preppedBody = services.prepGetAll(column, value, includedColumns, sorting, joined_tables, paging);
  console.log('body', preppedBody);
  let result = await services.getAll(table, preppedBody);

  if (result) {
    return joined_tables ? parseJoins(result, joined_tables) : result;
  }

  return null;
};

export const getAllMatching = async (table, filters,  includedColumns = null, sorting = null, joined_tables = null, paging = null) => {
  // let body = {
  //   filters,
  //   sorting,
  //   includedColumns
  // }

  // let data = await fetchData(table, "getAllMatching", null, null, null, null, body, "POST");
  // return data

  const preppedBody = services.prepGetAllMatching(filters, includedColumns, sorting, joined_tables, paging);
  let result = await services.getAllMatching(table, preppedBody);

  if (result) {
    return joined_tables ? parseJoins(result, joined_tables) : result;
  }

  return null;
};

export const create = async (table, createData) => {
  let body = {
    createData
  }
  // let data = await fetchData(table, "create", null, null, null, null, body, "POST");
  const data = await services.fetchData(table, "create", createData);
  return data;
};

export const deleteData = async(table, deleteData) => {
  let body = {
    deleteData
  }
  // let data = await fetchData(table, "delete", null, null, null, null, body, "POST");
  const data = await services.fetchData(table, "delete", deleteData);
  return data;
};

export const updateData = async(table, updateData) => {
  let body = {
    updateData
  }
  // let data = await fetchData(table, "update", null, null, null, null, body, "POST");
  const data = await services.fetchData(table, "update", deleteData);
  return data;
};

/*******************************************************************/
/****************************** USERS ******************************/
/*******************************************************************/

export const registerService = async (createData) => {
  console.log('registerService createData : ' ,createData);
  // let data = await create("users", createData);

  let data = await services.create("users", createData);
  return data
};

export const loginService = async (login) => {
  console.log('dddd ', login)
  let body = {
    login
  }
  const email = login.email;
  const password = login.password;
  
  console.log('email ', email );
  console.log('pass ', password );
  //console.log(body)
  // let data = await fetchData("users", "login", null, null, null, null, body, "POST");
  const data = await services.login(email, password);
  console.log('data log : ', data)
  return data
};

export const logoutService = async (logout) => {
  let body = {
    logout
  }
  // let data = await fetchData("users", "logout", null, null, null, null, body, "POST");
  const data = await services.logout();
  return data
};

export const getUser = async (developerID) => {
  return await getOne("users", "id", developerID)
};

export const getUsername = async (userID) => {
  return await getOne("users", "id", userID)
};


/*******************************************************************/
/****************************** GAMES ******************************/
/*******************************************************************/

const deleteGamesAndrelationships = async (jsonObject) => {
  const gameId = jsonObject["gameId"];
  const game = await getOne("games", "id", gameId);
  if (game) {
    await deleteData("games", jsonObject);
    const gamesTags = await getAll("gamesTags", "gameId", gameId);
    for (const game_tag of gamesTags) {
      await deleteData("gamesTags", { gameId: game_tag.gameId });
    }
    return true
  }
  return false
};

export const getGameDetails = async (idGame) => {
  let data = await getAll("games", "id", idGame);
  return data
};

export const getTags = async () => {
  let data = await getAll("tags");
  console.log('tags brut : ', data);
  return data
};

// GetAll
export const getAllGamesWithDeveloperNameNEW = async (column = null, value = null, includedColumns = null, sorting = null, paging = null) => {
  const joined_tables = { users: ['id', 'username', 'picture', 'isOnline'], tags: ['id', 'name'] };
  let data = await getAll('games', column, value, includedColumns, sorting, joined_tables, paging);
  return fetchGameImages(data);
}

// GetOne
export const getGameDetailsWithDeveloperNameNEW = async (gameID) => {
  const joined_tables = { users: ['id', 'username', 'picture', 'isOnline'], tags: ['id', 'name'] };

  let data = await getOne('games', 'id', gameID, null, joined_tables);
  return fetchOneGameImages(data);
}

export const getReviewsAndUsernamesNEW = async (gameID, sorting, paging = null) => {
  const joined_tables = {users: ['id', 'username', 'picture', 'isOnline']};

  return await getAll("reviews", "gameID", gameID, null, sorting, joined_tables, paging);
}

export const getGamesForCarousel = async () => {
  const filters = { ratingAverage: {gt: 1, lte: 7}};
  const sorting = { id: true };
  const included_columns = ['id', 'developerID', 'title', 'releaseDate', 'ratingAverage'];
  const joined_tables = {
      users: ['id', 'username', 'picture', 'isOnline'],
      tags: ['id', 'name']
    };

  let paging = { limit: 4, offset: 0};
  

  let data = await getAllMatching('games', filters, included_columns, sorting, joined_tables, paging);
  return fetchGameImages(data);
}

export const getGameReviews = async (gameID, sorting) => {
  const joined_tables = {users: ['id', 'username', 'picture']};

  return await getAll('reviews', 'gameID', gameID, null, sorting, joined_tables);
}

export const fetchGameImages  = async (games) => {
  try {
    const imageFetchPromises = games.map(async (game) => {
      //console.log('un gameee : ', game);
      const files = game.id || `defaultName.jpg`;
      const imagePath = `Games/${files}/media/${files}_0.png`;
      
      //console.log('imagePath : ', imagePath);
      const imageRef = ref(storage, imagePath);
      //console.log('imageRef : ', imageRef);

      try {
        const url = await getDownloadURL(imageRef);
        return { ...game, image: url };
      } catch (error) {
        console.error(`Error fetching image for ${game.title}:`, error);
        return { ...game, image: defaultImage }; // Fallback image
      }
    });

    return Promise.all(imageFetchPromises);
  } catch (error) {
    console.error("Error fetching game images:", error);
    return []; // Return an empty array in case of error
  }
}


export const fetchOneGameImages  = async (data) => {
  let gameId = data.id;
  try {
    const imagePath = `Games/${gameId}/media/${gameId}_Store.png`;
    //console.log('imagePath : ', imagePath);
    const imageRef = ref(storage, imagePath);

    try {
      const url = await getDownloadURL(imageRef);
      return { ...data, image: url }; // Corrected the return statement
    } catch (error) {
      console.error(`Error fetching image for ${gameId}:`, error);
      return { ...data, image: defaultImage };// Fallback image
    }
  } catch (error) {
    console.error("Error fetching game images:", error);
    throw error; // Re-throw the error to handle it at a higher level if needed
  }
}

/*** COUNT ***/

export const countAll = async (table, column = null, value = null) => {
  return await services.fetchData(table, 'countAll', {column: column, value: value});
}

export const countAllMatching = async (table, filters = []) => {
  return await services.fetchData(table, 'countAllMatching', {filters: filters});
}

/***************************************************/

export const getAllGamesWithDeveloperName = async (column = null, value = null, includedColumns = null, sorting = null, joined_tables = null, paging = null) => {
  try {
    const gamesArray = await getAll("games", column, value, includedColumns, sorting);
    // console.log("gamesArray : ", gamesArray);

    // Hold developer names in a Map for quick access
    const devNameMap = new Map();

    if (gamesArray && gamesArray.length) {
      // Fetch developer names and store in the Map
      await Promise.all(gamesArray.map(async (game) => {
        if (game.developerID && !devNameMap.has(game.developerID)) { // Check to avoid fetching the same developer name multiple times
          // console.log("game : ", game);
          const developerDetails = await getUsername(game.developerID);
          if (developerDetails && developerDetails.username) {
            devNameMap.set(game.developerID, developerDetails.username);
          }
        }
      }));

      // console.log("devNameMap : ", devNameMap)
      const gamesWithDevNames = gamesArray.map(game => {
        const devName = game.developerID ? devNameMap.get(game.developerID) : undefined;
        return {...game, devName}; // Create a new enriched game object
      });
      // console.log("gamesWithDevNames : ", gamesWithDevNames)
      return gamesWithDevNames;
    }

    return gamesArray;
  } catch (error) {
    console.error("Error fetching all games with developer names:", error);
    return [];
  }
};

export const getGameDetailsWithDeveloperName = async (idGame) => {
  try {
    const gameDetailsArray = await getGameDetails(idGame);
    // console.log("getGameDetailsWithDeveloperName gameDetails : ", gameDetailsArray);


    if (gameDetailsArray && gameDetailsArray.length > 0) {
      const gameDetails = gameDetailsArray[0];
      // console.log("gameDetails.developerID : ", gameDetails.developerID);

      const developerDetails = await getUsername(gameDetails.developerID);
      if (developerDetails && developerDetails.username) {

        gameDetails.devName = developerDetails.username;
        // console.log("gameDetails.devName : ", gameDetails.devName);
      }

      return gameDetails;
    }
  } catch (error) {
    console.error("Error fetching game details and developer name:", error);
    return null;
  }
};


/*******************************************************************/
/***************************** REVIEWS *****************************/
/*******************************************************************/

// export const createReview = async (jsonObject) => {
//   return await create("reviews", jsonObject);
// };


export const createReviews = async (table, createData) => {
  let body = {
    createData
  }
  let data = await fetchData(table, "create", null, null, null, null, body, "POST");
  return data;
};

export const getReviews = async (gameID) => {
  return await getAll("reviews", "gameID", gameID);
};

export const getReviewsAndUsernames = async (gameID, sorting) => {
  // console.log(" getReviewsAndUsernames sorting : ", sorting)
  // console.log(" getReviewsAndUsernames gameID : ", gameID)

  if (reviews && reviews.length) {
    const reviewsWithUsernames = await Promise.all(reviews.map(async (review) => {
      if (review.userID) {
        const userDetails = await getUsername(review.userID);
        if (userDetails) {
          review.username = userDetails.username;
          review.profilePic = userDetails.picture;
        }
      }

      return review;
    }));

    return reviewsWithUsernames;
  }

  return reviews;
};

export const getGameReviewsUsernames = async (gameID, sorting = null) => {
  try {
    const gameDetailsArray = await getGameDetails(gameID);
    let results = {};


    if (gameDetailsArray && gameDetailsArray.length > 0) {
      const gameDetails = gameDetailsArray[0];

      if (gameDetails && gameDetails.developerID) {
        results.game = gameDetails;

        let fullReviews = await getReviewsAndUsernames(gameDetails.id, sorting);
        results.reviews = fullReviews;
      }
    }

    return results;
  } catch (error) {
    console.error("Error in getGameReviewsUsernames:", error);
    return { game: {}, reviews: [] };
  }
};


/*******************************************************************/
/****************************** TAGS *******************************/
/*******************************************************************/

export const filterSearchedTags = async (searchedTag) => {
  let filters = {
    name: { contain: searchedTag }
  }
  let data = await getAllMatching("tags", filters);
  return data
};