VERSION 1.0 CLASS
BEGIN
  MultiUse = -1  'True
END
Attribute VB_Name = "Hdr"
Attribute VB_GlobalNameSpace = False
Attribute VB_Creatable = False
Attribute VB_PredeclaredId = False
Attribute VB_Exposed = True
Option Explicit
Public A_Dic As Dictionary
Public A_Ft$
Public TripChiNm$
Public Driver$
Public DriverTy$
Public Member$
Public Leader$

Function NewHdr(Seg$) As Hdr
A_Ft = LSPth.Que & Seg & "\Hdr.txt"

Set A_Dic = NewDyByFt(A_Ft).A_Dic

Dim A As Dictionary
    Set A = A_Dic
TripChiNm = A("TripChiNm")
   Driver = A("Driver")
 DriverTy = A("DriverTy")
   Member = A("Member")
   Leader = A("Leader")
Set NewHdr = Me
End Function

Function BrwFt() As Hdr
FtBrw A_Ft
End Function

Function Brw() As Hdr
Dim T$
T = TmpFt
A_Dic.WrtFt T
      FtBrw T
End Function

Sub FillWs(Ws As Worksheet)
WsFillCellByDic Ws, A_Dic
End Sub
