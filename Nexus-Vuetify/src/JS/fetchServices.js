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
  const reviews = await fetchData("reviews", "getAll", "gameID", gameID, null, null, sorting, "GET");

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
    const gameDetails = await getGameDetails(idGame);

    if (gameDetails && gameDetails.developerID) {
      const developerDetails = await getUsername(gameDetails.developerID);

      if (developerDetails && developerDetails.username) {
        gameDetails.developerName = developerDetails.username;
      }
    }

    return gameDetails;
  } catch (error) {
    console.error("Error fetching game details and developer name:", error);
    return null; 
  }
};


export const getGameReviewsUsernames = async (gameID, sorting = null) => {
  const gameDetails = await getGameDetails(gameID);
  let results = {}
  if (gameDetails && gameDetails.developerID) {
    results.game = gameDetails
    let fullReviews = await getReviewsAndUsernames(gameDetails.id, sorting)
    results.reviews = fullReviews
  }

  return results;
};



