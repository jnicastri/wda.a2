DELIMTER $$

CREATE PROCEDURE MediaItem_GetAllByItemId(
	IN itmId int(8))
BEGIN
	SELECT
		M.*
	FROM
		MediaItem M
	WHERE
		M.ItemId = itmId;
END$$