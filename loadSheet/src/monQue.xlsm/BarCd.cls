VERSION 1.0 CLASS
BEGIN
  MultiUse = -1  'True
END
Attribute VB_Name = "BarCd"
Attribute VB_GlobalNameSpace = False
Attribute VB_Creatable = False
Attribute VB_PredeclaredId = False
Attribute VB_Exposed = False
Option Explicit
Public BarCdFfn$
Function Init(Fdr$) As BarCd
BarCdFfn = PthQue & Fdr & "\BarCd.png"
Set Init = Me
End Function

Function PutBarCd(Ws As Worksheet) As Boolean
Dim Shp As Shape
    Set Shp = Ws.Shapes("BarCode")

If Not IsFile(BarCdFfn) Then
    WrtLog "BarCd.PutBarCd: BarCode file not found: [" & BarCdFfn & "]"
    PutBarCd = True
    Exit Function
End If

Dim T!, L!, H!, W!
T = Shp.Top
L = Shp.Left
H = Shp.Height
W = Shp.Width
Shp.Delete

WsPutImgFfn Ws, BarCdFfn, T, L, H, W, "BarCode"
End Function

