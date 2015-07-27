Attribute VB_Name = "XlsRge"
Option Explicit

Function RgePutImgFfn(Rge As Range, ImgFfn$, Optional PicNm$ = "") As Shape
If Not IsFile(ImgFfn) Then Exit Function
Dim O As Shape, Ws As Worksheet
Set Ws = Rge.Worksheet
Dim L!, T!, W!, H!
L = Rge.Left + 2
T = Rge.Top + 2
W = 200
H = 200
Set O = Ws.Shapes.AddPicture(ImgFfn, msoFalse, msoCTrue, L, T, W, H)
If PicNm <> "" Then O.Name = PicNm
Set RgePutImgFfn = O
End Function



Sub RgeSetBdrGrid(Rge As Range)
Rge.BorderAround XlLineStyle.xlContinuous, xlMedium
With Rge.Borders(XlBordersIndex.xlInsideVertical)
    .LineStyle = xlContinuous
    .Weight = XlBorderWeight.xlThin
End With
With Rge.Borders(XlBordersIndex.xlInsideHorizontal)
    .LineStyle = xlContinuous
    .Weight = XlBorderWeight.xlThin
End With
Rge.BorderAround XlLineStyle.xlContinuous, xlMedium
With Rge.Borders(XlBordersIndex.xlInsideVertical)
    .LineStyle = xlContinuous
    .Weight = XlBorderWeight.xlThin
End With
With Rge.Borders(XlBordersIndex.xlInsideHorizontal)
    .LineStyle = xlContinuous
    .Weight = XlBorderWeight.xlThin
End With
End Sub

