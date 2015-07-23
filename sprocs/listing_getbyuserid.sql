DELIMITER $$

CREATE PROCEDURE Listing_GetByUserId(
	IN userId INT(8),
	IN statusInt INT(2)
)
BEGIN
	
	DECLARE now DATETIME;
	SET now = NOW();
	
	IF statusInt = 3 THEN
		SELECT L.*
		FROM Listing L
		WHERE L.UserId = userId
		ORDER BY L.ListedDate DESC;
	ELSEIF statusInt = 2 THEN
		SELECT L.*
		FROM Listing L
		WHERE L.UserId = userId AND L.ListedDate > now
		ORDER BY L.ListedDate DESC;
	ELSEIF statusInt = 1 THEN
		SELECT L.*
		FROM Listing L
		WHERE L.UserId = userId AND L.EndDate < now
		ORDER BY L.ListedDate DESC;
	ELSE
		SELECT L.*
		FROM Listing L
		WHERE L.UserId = userId AND (L.ListedDate < now AND L.EndDate > now)
		ORDER BY L.ListedDate DESC;
	END IF;
END $$