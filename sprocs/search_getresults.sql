DELIMITER $$

CREATE PROCEDURE Search_GetResults(
	IN queryTerm varchar(100))
BEGIN

DECLARE NowDt DATETIME;
SET NowDt = NOW();

SELECT 
	L.Id AS ListingId,
	I.`Name` AS ItemName,
	C.CategoryName,
	L.EndDate,
	M.`FileName` AS MediaFileName
FROM 
	Item I 
	LEFT JOIN Listing L ON I.Id = L.ItemId
	LEFT JOIN MediaItem M ON I.Id = M.ItemId AND M.IsPrimary = 1 AND M.IsActive = 1 
	LEFT JOIN Category C ON I.CategoryId = C.Id
WHERE
	I.`Name` LIKE CONCAT('%', queryTerm, '%')
	AND L.EndDate > NowDt
ORDER BY
	I.`Name`;
END$$ 
