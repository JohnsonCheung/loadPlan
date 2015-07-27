Attribute VB_Name = "VbFt__Tst"
Option Explicit

Sub FtBrw__Tst()
Open "C:\Temp\a.txt" For Output As #1
Print #1, "asdfsdf"
Print #1, "lsdkfjsdlf"
Close #1
FtBrw "C:\Temp\a.txt"
End Sub


