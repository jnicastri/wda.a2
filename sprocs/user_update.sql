DELIMITER $$

CREATE PROCEDURE User_Update(
	IN id INT(8),
	IN firstName VARCHAR(60),
	IN lastName VARCHAR(60),
	IN email VARCHAR(50),
	IN userName VARCHAR(50),
	IN bAddresLine1 VARCHAR(60),
	IN bAddresLine2 VARCHAR(60),
	IN bAddressSuburb VARCHAR(30),
	IN bAddressState VARCHAR(10),
	IN bAddressZip VARCHAR(10)
)
BEGIN
	
	UPDATE 
		UserDetail U
	SET
		U.FirstName = firstName,
		U.LastName = lastName,
		U.Email = email,
		U.DisplayUserName = userName,
		U.BillingAddressLine1 = bAddresLine1,
		U.BillingAddressLine2 = bAddresLine2,
		U.BillingAddressSuburb = bAddressSuburb,
		U.BillingAddressState = bAddressState,
		U.BillingAddressZip = bAddressZip
	WHERE
		U.Id = id;
	
END$$