DELIMTER $$

CREATE PROCEDURE User_Load(
	IN userId int(4))
BEGIN
	SELECT
		U.Id,
		U.DateCreated,
		U.FirstName,
		U.LastName,
		U.Email,
		U.DisplayUserName,
		U.BillingAddressLine1,
		U.BillingAddressLine2,
		U.BillingAddressSuburb,
		U.BillingAddressState,
		U.BillingAddressZip
	FROM
		UserDetail U
	WHERE
		U.Id = userId;
END$$