VERSION 1.0 CLASS
BEGIN
  MultiUse = -1  'True
END
Attribute VB_Name = "Venv"
Attribute VB_GlobalNameSpace = False
Attribute VB_Creatable = False
Attribute VB_PredeclaredId = False
Attribute VB_Exposed = True
Option Explicit
Public A_Vbe As Vbe
Sub Init(Vbe As Vbe)
Set A_Vbe = Vbe
End Sub
Function Pj(PjNm$) As Pj
Dim O As New Pj
O.Init A_Vbe.VBProjects(PjNm)
Set Pj = O
End Function
