Attribute VB_Name = "XlsWs"
Option Explicit

Sub WsFillCellByDic(Ws As Worksheet, Dic As Dictionary)
Dim K(), J%
K = Dic.Keys
For J = 0 To UB(K)
    Ws.Names(K(J)).RefersToRange.Value = Dic(K)
Next
End Sub

Sub WsCls(Ws As Worksheet)
WsWb(Ws).Close False
End Sub

Function WsWb(Ws As Worksheet) As Workbook
Set WsWb = Ws.Parent
End Function

Sub WsFillRowByDic(Ws As Worksheet, Rno&, Dic As Dictionary)
Dim K$(), J%, Cno%, Rge As Range
K = Dic.Keys
For J = 0 To UB(K)
    Cno = Ws.Names(K(J)).RefersToRange.Column
    Set Rge = Ws.Cells(Rno, Cno)
    Rge.Value = Dic(K)
Next
End Sub

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

