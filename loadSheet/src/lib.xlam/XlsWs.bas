Attribute VB_Name = "XlsWs"
Option Explicit

Sub WsFillCellByDic(Ws As Worksheet, Dic As Dictionary)
Dim K(), J%
K = Dic.Keys
For J = 0 To UB(K)
    Ws.Names(K(J)).RefersToRange.Value = Dic(K(J))
Next
End Sub

Sub WsCls(Ws As Worksheet)
WsWb(Ws).Close False
End Sub

Function WsWb(Ws As Worksheet) As Workbook
Set WsWb = Ws.Parent
End Function

Function WsRCRC(Ws As Worksheet, R1, C1, R2, C2) As Range
Set WsRCRC = Ws.Range(WsRC(Ws, R1, C1), WsRC(Ws, R2, C2))
End Function

Sub WsFillRowByDic(Ws As Worksheet, Rno&, Dic As Dictionary, Optional NmPfx$ = "", Optional Span2Lvs$ = "", Optional AlignLeftLvs$ = "")
Dim K(), J%, Cno%, Rge As Range, Nm As Name
K = Dic.Keys
For J = 0 To UB(K)
    Set Nm = Ws.Names(NmPfx & K(J))
    Cno = Nm.RefersToRange.Column
    Set Rge = Ws.Cells(Rno, Cno)
    Rge.Value = UnEscLF(Dic(K(J)))
Next
WsR(Ws, Rno).AutoFit
Dim Ay$()
    Ay = Split(Span2Lvs, " ")

Dim C%
For J = 0 To UB(Ay)
    Set Nm = Ws.Names(NmPfx & Ay(J))
    C = Nm.RefersToRange.Column
    WsRCC(Ws, Rno, C, C + 1).MergeCells = True
Next

Dim Ay1$()
    Ay1 = Split(AlignLeftLvs, " ")
For J = 0 To UB(K)
    Set Nm = Ws.Names(NmPfx & K(J))
    Cno = Nm.RefersToRange.Column
    Set Rge = Ws.Cells(Rno, Cno)
    Dim A As XlHAlign
    A = IIf(AyHas(Ay1, K(J)), xlHAlignLeft, xlHAlignCenter)
    Rge.HorizontalAlignment = A
Next
End Sub

Function WsR(Ws As Worksheet, R) As Range
Dim O As Range
Set O = Ws.Rows(R)
Set WsR = O.EntireRow
End Function

Function WsRC(Ws As Worksheet, R, C) As Range
Set WsRC = Ws.Cells(R, C)
End Function

Function WsRCC(Ws As Worksheet, R, C1, C2) As Range
Set WsRCC = Ws.Range(WsRC(Ws, R, C1), WsRC(Ws, R, C2))
End Function

Function WsNew(Optional WsNm$ = "Sheet1") As Worksheet
Dim Wb  As Workbook, Ws As Worksheet
Set Wb = WbNew
Wb.Application.DisplayAlerts = False
Set Ws = Wb.Sheets(2): Ws.Delete
Set Ws = Wb.Sheets(2): Ws.Delete
Wb.Application.DisplayAlerts = True
Set WsNew = Wb.Sheets(1)
End Function

Function WsPutImgFfn(Ws As Worksheet, ImgFfn$, T!, L!, H!, W!, Optional PicNm$ = "") As Shape
If Dir(ImgFfn) = "" Then Exit Function
Dim O As Shape
Set O = Ws.Shapes.AddPicture(ImgFfn, msoFalse, msoCTrue, L, T, W, H)
If PicNm <> "" Then O.Name = PicNm
Set WsPutImgFfn = O
End Function
