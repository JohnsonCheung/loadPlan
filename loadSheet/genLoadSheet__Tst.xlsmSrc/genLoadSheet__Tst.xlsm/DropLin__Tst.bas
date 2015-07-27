Attribute VB_Name = "DropLin__Tst"
Option Explicit
Const Inspect = False
Sub TstAll_DropLin()
NewDropLin__Tst
FillWs__Tst
End Sub

Private Sub NewDropLin__Tst()
CpyQue2
Dim M As DropLin
Set M = NewDropLin(C_Seg2, "Drop-01.txt")
Debug.Assert M.Adr = "Adr-Line#1\nAdr-Line#2\nAdr-Line#3 Long-Long-Line sldkjf lskdj flskdj flskdj f\nAdr-Line#4"
Debug.Assert M.AdrContact = "AdrContact1"
Debug.Assert M.AdrPhone = "AdrPhone1"
Debug.Assert M.Content = "Content-Line#1  sdlf sdlf sldkf skdl fslkdjf skdljf ksdj fklsd flksd jfklsd jflkds flskd f\nContent-Line#2 lksdjf\nContent-Line#3"
Debug.Assert M.Cus = "Cust"
Debug.Assert M.OrdBy = "OrdBy1"
Debug.Assert M.OrdNo = "OrdNo#1\nAdr#2\nDrop#1"
Debug.Assert M.PagList = "1, 2, 3"
Debug.Assert M.Qty = "123 CBM"
Debug.Assert M.RmkOfAdr = "@1"
Debug.Assert M.RmkOfOrd = "*1"
Pass "NewDropLin__Tst"
End Sub

Private Sub FillWs__Tst()
CpyQue2
Dim Ws As Worksheet
Dim Rno&
Dim M As DropLin
    Set Ws = NewWs1(C_Seg2).AddWs1(WbNew)
    Rno = 1
    Set M = NewDropLin(C_Seg2, "Drop-01.txt")

M.FillWs Ws, 12
If Inspect Then
    Application.Visible = True
    Stop
End If
WsWb(Ws).Close False
Pass "FillWs__Tst"
End Sub


