VERSION 1.0 CLASS
BEGIN
  MultiUse = -1  'True
END
Attribute VB_Name = "DropLin"
Attribute VB_GlobalNameSpace = False
Attribute VB_Creatable = False
Attribute VB_PredeclaredId = False
Attribute VB_Exposed = True
Option Explicit
Public A_Dic As Dictionary
Public Adr As String
Public AdrCd As String
Public AdrContact As String
Public AdrPhone As String
Public Content As String
Public Cus As String
Public OrdBy As String
Public OrdNo As String
Public PagList As String
Public Qty As String
Public RmkOfAdr As String
Public RmkOfOrd As String
Function NewDropLin(Seg$, Fn$) As DropLin
Dim F$
    F = LSPth.Que & Seg & "\" & Fn

Dim D As Dictionary
Set A_Dic = NewDyByFt(F).A_Dic
     Set D = A_Dic
     OrdNo = D("OrdNo")
       Cus = D("Cus")
     AdrCd = D("AdrCd")
       Adr = D("Adr")
   Content = D("Content")
       Qty = D("Qty")
   PagList = D("PagList")
     OrdBy = D("OrdBy")
AdrContact = D("AdrContact")
  AdrPhone = D("AdrPhone")
  RmkOfOrd = D("RmkOfOrd")
  RmkOfAdr = D("RmkOfAdr")
Set NewDropLin = Me
End Function

Sub FillWs(Ws As Worksheet, Rno&)
WsFillRowByDic Ws, Rno, A_Dic, NmPfx:="L_", AlignLeftLvs:="Adr Content"
End Sub
