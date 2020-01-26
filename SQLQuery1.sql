SELECT * FROM OPENROWSET('Microsoft.ACE.OLEDB.12.0', 'Data Source=C:\Users\aroos\Desktop\College\Fall2019\Database21tt.mdb;"', [Sheet 1$])

SELECT * FROM OPENROWSET('Microsoft.ACE.OLEDB.12.0', 
    'Excel 12.0;HDR=NO;Database=C:\Users\aroos\Desktop\College\Fall2019\Book12.xls;', 
    [Sheet1$])

	SELECT * FROM OPENROWSET(
'Microsoft.ACE.OLEDB.12.0'
,'Excel 12.0;Database=C:\Users\aroos\Desktop\College\Fall2019\Book12.xls;HDR=YES'
,'SELECT * FROM [Sheet1$]')


	EXEC master.dbo.sp_MSset_oledb_prop N'Microsoft.ACE.OLEDB.12.0'

    , N'AllowInProcess', 1

GO


EXEC master.dbo.sp_MSset_oledb_prop N'Microsoft.ACE.OLEDB.12.0'

    , N'DynamicParameters', 1

GO

SELECT * FROM OPENROWSET('Microsoft.ACE.OLEDB.12.0', 
    'HDR=NO;Database=C:\Users\aroos\Desktop\College\Fall2019\Database21tt.mdb;', 
    [ConceptList])
