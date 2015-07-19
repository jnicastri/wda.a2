DELIMTER $$

CREATE PROCEDURE Category_GetAll()
BEGIN
	SELECT
		C.Id,
		C.CategoryName,
		C.CategoryDescription
	FROM
		Category C
	ORDER BY
		C.CategoryName;
END$$