import { fetchData } from "./fetch";



export const getGameDetails = async (idGame) => {
  return await fetchData("games", "getOne", "id", idGame, null, null, null, "GET");
};

export const getUsername = async (userID) => {
  return await fetchData("users", "getOne", "id", userID, null, null, null, "GET");
};

export const getReviews = async (gameID) => {
  return await fetchData("reviews", "getAll", "gameID", gameID, null, null, null, "GET");
};


export const getReviewsAndUsernames = async (gameID, sorting = null) => {
  // console.log(" getReviewsAndUsernames sorting : ", sorting)
  // console.log(" getReviewsAndUsernames gameID : ", gameID)
  const reviews = await fetchData("reviews", "getAll", "gameID", gameID, null,  sorting, null, "GET");

  if (reviews && reviews.length) {
    const reviewsWithUsernames = await Promise.all(reviews.map(async (review) => {
      if (review.userID) {
        const userDetails = await getUsername(review.userID);
        if (userDetails) {
          review.username = userDetails.username;
        }
      }
      return review;
    }));

    return reviewsWithUsernames;
  }

  return reviews;
};


export const getGameDetailsWithDeveloperName = async (idGame) => {
  try {
    const gameDetailsArray = await getGameDetails(idGame);
    console.log("getGameDetailsWithDeveloperName gameDetails : ", gameDetailsArray);

    
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

