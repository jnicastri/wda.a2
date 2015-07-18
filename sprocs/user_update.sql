DELIMITER $$

CREATE PROCEDURE User_Update(
	IN id INT(8),
	IN firstName VARCHAR(60),
	IN lastName VARCHAR(60),
	IN email VARCHAR(50),
	IN userName VARCHAR(50),
	IN userPwd VARCHAR(200),
	IN bAddresLine1 VARCHAR(60),
	IN bAddresLine2 VARCHAR(60),
	IN bAddressSuburb VARCHAR(30),
	IN bAddressState VARCHAR(10),
	IN bAddressZip VARCHAR(10)
)
BEGIN
	
	UPDATE 
		UserDetail
	SET
		FirstName = firstName,
		LastName = lastName,
		Email = email,
		DisplayUserName = userName,
		BillingAddressLine1 = bAddresLine1,
		BillingAddressLine2 = bAddresLine2,
		BillingAddressSuburb = bAddressSuburb,
		BillingAddressState = bAddressState,
		BillingAddressZip = bAddressZip
	WHERE
		Id = id;
	
END