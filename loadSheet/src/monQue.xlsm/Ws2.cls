VERSION 1.0 CLASS
BEGIN
  MultiUse = -1  'True
END
Attribute VB_Name = "Ws2"
Attribute VB_GlobalNameSpace = False
Attribute VB_Creatable = False
Attribute VB_PredeclaredId = False
Attribute VB_Exposed = False
Attribute VB_Description = "Attachment --- Delivery Content"
Option Explicit
Private A_Fdr$
Private A_AttFnAy$()

Function Name$()
Name = WsAtt.Name
End Function

Function Init(Fdr$) As Ws2
A_Fdr = Fdr
A_AttFnAy = PthAyFnLik(PthQue & Fdr & "\", "Trip-####-##-##?### att-## (ord-####-##-##?#### content-##).png")
Dim J%
For J = 0 To UB(A_AttFnAy)
    If Len(A_AttFnAy(J)) <> 63 Then WrtLog "AttFnAy(" & J & ") len <> 63, but = [" & Len(A_AttFnAy(J)) & "].  [" & A_AttFnAy(J) & "]"
Next
Set Init = Me
End Function
Property Get AttFnAy() As String()
AttFnAy = A_AttFnAy
End Property

Function Fillin(Wb As Workbook) As Boolean
Wb.Application.ScreenUpdating = False
WsAtt.Copy Wb.Sheets(1)
Dim Ws As Worksheet
Set Ws = Wb.Sheets(1)
Dim J%, ORno%
ORno = 1
If Sz(A_AttFnAy) = 0 Then
    Ws.Range("A1").Value = "No attachement pages"
Else
    For J = 0 To UB(A_AttFnAy)
        If PutOneAtt(Ws, ORno, A_AttFnAy(J)) Then Fillin = True
    Next
End If
Ws.PageSetup.CenterFooter = "&P 之 &N"
End Function

Function PutOneAtt(Ws As Worksheet, ByRef ORno%, AttFn$) As Boolean
'AttFn fmt = Trip-2015-01-01#001 Att-02 (Ord-2015-01-01#1234 Content-02).png
'            123456789012345678901234567890123456789012345678901234567890123
'                     1         2         3         4         5         6
'Fdr   fmt = C:\xampp\htdocs\loadPlan\pgm\loadSheet\QueTest\trip-2015-01-01#002
Dim Fdr$
Fdr = A_Fdr
If ORno <> 1 Then Ws.HPageBreaks.Add Before:=Ws.Range("A" & ORno)      '<== Set page break

Dim A$, J%

Dim TripNm$, OrdNm$, AttNo%, ContentNo% '<- AttFn
    A = AttFn
    TripNm = Mid(A, 6, 20 - 6)
    AttNo = Mid(A, 25, 2)
    OrdNm = Mid(A, 33, 48 - 33)
    ContentNo = Mid(A, 57, 2)

Dim Nm$(1, 1), V$(1, 1)
Nm(0, 0) = "行程": V(0, 0) = TripNm: Nm(0, 1) = "附頁":     V(0, 1) = AttNo
Nm(1, 0) = "柯打": V(1, 0) = OrdNm:  Nm(1, 1) = "送貨內容": V(1, 1) = ContentNo

Ws.Range("A" & ORno).Value = Nm(0, 0) '<== Put Nm
Ws.Range("B" & ORno).Value = V(0, 0)  '<== Put Nm
Ws.Range("C" & ORno).Value = Nm(0, 1)  '<== Put V
Ws.Range("D" & ORno).Value = V(0, 1)   '<== Put V
ORno = ORno + 1                        '<== ORno++

Ws.Range("A" & ORno).Value = Nm(1, 0) '<== Put Nm
Ws.Range("B" & ORno).Value = V(1, 0)  '<== Put Nm
Ws.Range("C" & ORno).Value = Nm(1, 1) '<== Put V
Ws.Range("D" & ORno).Value = V(1, 1)  '<== Put V
ORno = ORno + 1                       '<== ORno++

Dim Ffn$
    Ffn = PthQue & Fdr & "\" & AttFn

If Not IsFile(Ffn) Then
    WrtLog vbTab & "Order content page not file: [" & Ffn & "]"
    PutOneAtt = True                                            '<== Return True for err
    Ws.Range("A" & ORno).Value = "Image file not found!!"       '<== Put "Img file not found
    Ws.Range("B" & ORno + 1).Value = "Path"                     '<==
    Ws.Range("C" & ORno + 1).Value = FfnPth(Ffn)   '<==
    Ws.Range("B" & ORno + 2).Value = "File"        '<==
    Ws.Range("C" & ORno + 2).Value = FfnFn(Ffn)    '<==
    ORno = ORno + 3                                '<== ORno++
Else
    ORno = ORno + 1

    Dim Shp As Shape
    Dim AtRge As Range
    Dim PIC As Picture
    Set AtRge = Ws.Range("A" & ORno)
    Set Shp = RgePutImgFfn(AtRge, Ffn) '<=== Put Image
    Set PIC = Shp.OLEFormat.Object
    PIC.ShapeRange.ScaleHeight 1, msoFalse, msoScaleFromTopLeft
    PIC.ShapeRange.ScaleWidth 1, msoFalse, msoScaleFromTopLeft
    
    ORno = ShpNxtRno(Shp, Ws.Range("A" & ORno))    '<=== Set ORno
End If
End Function



