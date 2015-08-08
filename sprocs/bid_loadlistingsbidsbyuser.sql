DELIMITER $$

CREATE PROCEDURE Bid_LoadListingsBidsByUser(
	IN userId INT
)
BEGIN
	
	SELECT
		B.Id AS BidId,
		L.Id AS ListingId,
		L.EndDate AS ListingEndDate,
		I.`Name` AS ItemName,
		B.`Status` AS BidStatus,
		B.BidValue
	FROM
		Bid B
		LEFT JOIN Listing L ON B.ListingId = L.Id
		LEFT JOIN Item I ON L.ItemId = I.Id
	WHERE
		B.BiddingUserId = userId
	ORDER BY 
		L.EndDate ASC,
		B.BidValue DESC;
	
END $$