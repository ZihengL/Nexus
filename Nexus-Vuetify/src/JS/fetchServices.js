import { fetchData } from "./fetch";
import { fetchData2 } from "./fetchData";

export const getOne = async (table, column, value, included_columns = null, sorting = null) => {
  const body = {
    column,
    value,
    included_columns,
    sorting
  }

  // let data = await fetchData(table, "getOne", column, value, included_columns, sorting, null, "GET");
  let data = await fetchData2(table, 'getOne', body);
  return data;
};

export const getAll = async (table, column = null, value = null, included_columns = null, sorting = null) => {
  const body = {
    column,
    value,
    included_columns,
    sorting
  }

  // let data = await fetchData(table, "getAll", column, value, included_columns, sorting, null, "GET");
  let data = await fetchData2(table, 'getAll', body);

export const getAll = async (table, column = null, value = null, includedColumns = null, sorting = null) => {
  let data = await fetchData(table, "getAll", column, value, includedColumns, sorting, null, "GET");
  return data
};

export const getAllMatching = async (table, filters, sorting = null, included_columns = null) => {
export const getAllMatching = async (table, filters,  includedColumns = null, sorting = null) => {
  let body = {
    filters,
    sorting,
    included_columns
  }

  // let data = await fetchData(table, "getAllMatching", null, null, null, null, body, "POST");
  let data = await fetchData2(table, 'getAllMatching', body);
  return data
};

/*******************************************************************/
/****************************** USERS ******************************/
/*******************************************************************/

export const registerService = async (createData) => {
export const create = async (table, createData) => {
  let body = {
    createData
  }
  let data = await fetchData(table, "create", null, null, null, null, body, "POST");
  return data;
};

export const deleteData = async(table, deleteData) => {
  let body = {
    deleteData
  }
  let data = await fetchData(table, "delete", null, null, null, null, body, "POST");
  return data;
};

export const updateData = async(table, updateData) => {
  let body = {
    createData
    updateData
  }
  let data = await fetchData(table, "update", null, null, null, null, body, "POST");
  return data;
};

/*******************************************************************/
/****************************** USERS ******************************/
/*******************************************************************/

export const registerService = async (createData) => {
  console.log('registerService createData : ' ,createData);

  let data = await create("users", createData)
  return data
};

export const loginService = async (login) => {
  //console.log(jsonObject)
  let body = {
    login
  }
  //console.log(body)
  let data = await fetchData("users", "login", null, null, null, null, body, "POST");
  return data
};

export const logoutService = async (logout) => {
  let body = {
    logout
  }
  let data = await fetchData("users", "logout", null, null, null, null, body, "POST");
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

export const getGameDetails = async (idGame) => {
  let data = await getAll("games", "id", idGame);
  return data
};

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


// CHANGE HERE FOR GETALLMATCHING
export const getAllGamesWithDeveloperName = async (column = null, value = null, includedColumns = null, sorting = null) => {
  try {
    const gamesArray = await getAll("games", column, value, includedColumns, sorting);

    if (gamesArray && gamesArray.length) {


      const gamesWithDevNames = await Promise.all(gamesArray.map(async (game) => {
        if (game.developerID) {
          const developerDetails = await getUsername(game.developerID);
          if (developerDetails && developerDetails.username) {


            game.devName = developerDetails.username;
          }
        }
        return game;
      }));

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
  const reviews = await getAll("reviews", "gameID", gameID, null, sorting, null);

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

        results.game = gameDetails;

        let fullReviews = await getReviewsAndUsernames(gameDetails.id, sorting);
        results.reviews = fullReviews;
      }
    }

    return results;
  } catch (error) {
    console.error("Error in getGameReviewsUsernames:", error);
    return { game: {}, reviews: [] };
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