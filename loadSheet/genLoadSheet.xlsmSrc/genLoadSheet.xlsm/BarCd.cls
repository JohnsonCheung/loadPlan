VERSION 1.0 CLASS
BEGIN
  MultiUse = -1  'True
END
Attribute VB_Name = "BarCd"
Attribute VB_GlobalNameSpace = False
Attribute VB_Creatable = False
Attribute VB_PredeclaredId = False
Attribute VB_Exposed = True
Option Explicit
Public A_BarCdFfn$
Function NewBarCd(Seg$) As BarCd
A_BarCdFfn = LSPth.Que & Seg & "\BarCd.png"
Set NewBarCd = Me
End Function

Sub PutBarCd(Ws As Worksheet)
Dim Shp As Shape
    Set Shp = Ws.Shapes("BarCode")

If Not IsFile(A_BarCdFfn) Then
    Logr.WrtMsg "BarCd.PutBarCd: BarCode file not found: [" & A_BarCdFfn & "]"
    Exit Sub
End If

Dim T!, L!, H!, W!
T = Shp.Top
L = Shp.Left
H = Shp.Height
W = Shp.Width
Shp.Delete
WsPutImgFfn Ws, A_BarCdFfn, T, L, H, W, "BarCode"
End Sub

