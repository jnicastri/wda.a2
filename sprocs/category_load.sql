DELIMTER $$

CREATE PROCEDURE Category_Load(
	IN catId int(8))
BEGIN
	SELECT
		C.Id,
		C.CategoryName,
		C.CategoryDescription
	FROM
		Category C
	WHERE
		C.Id = catId;
END$$