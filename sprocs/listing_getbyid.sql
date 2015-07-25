DELIMITER $$

CREATE PROCEDURE Listing_GetById(
	IN listingId INT(8)
)
BEGIN
	SELECT L.*
	FROM Listing L
	WHERE L.Id = listingId
	LIMIT 1;
END $$