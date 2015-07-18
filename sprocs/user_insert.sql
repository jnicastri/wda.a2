DELIMITER $$

CREATE PROCEDURE User_Insert(
	IN firstName VARCHAR(60),
	IN lastName VARCHAR(60),
	IN email VARCHAR(50),
	IN userName VARCHAR(50),
	IN userPwd VARCHAR(200),
	IN bAddresLine1 VARCHAR(60),
	IN bAddresLine2 VARCHAR(60),
	IN bAddressSuburb VARCHAR(30),
	IN bAddressState VARCHAR(10),
	IN bAddressZip VARCHAR(10),
	OUT id INT(8),
	OUT createdDate DATETIME
)
BEGIN
	DECLARE insDT DATETIME;
	SET insDT = NOW();
	
	INSERT INTO UserDetail
		(DateCreated, FirstName, LastName, Email, DisplayUserName,
		 `Password`, BillingAddressLine1, BillingAddressLine2, BillingAddressSuburb,
		 BillingAddressState, BillingAddressZip)
	VALUES
		(insDT, firstName, lastName, email, userName, userPwd,
		bAddresLine1, bAddresLine2, bAddressSuburb, bAddressState, bAddressZip);
		
	SELECT LAST_INSERT_ID() INTO id;
	SELECT insDT INTO createdDate;
	
		
END$$