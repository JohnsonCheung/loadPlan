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
Private Type Att
    CusCd As String
    ContentRmk As String
    AttFn As String
End Type
Private A_Fdr$
Private A_AttAy() As Att

Function Name$()
Name = WsAtt.Name
End Function

Sub Init(Fdr$)
A_Fdr = Fdr
A_AttAy = Init__AttAy
Dim J%, M As Att, AttFn$
For J = 0 To NAtt - 1
    M = A_AttAy(J)
    AttFn = M.AttFn
    If Len(AttFn) <> 63 Then WrtLog "AttFnAy(" & J & ") len <> 63, but = [" & Len(AttFn) & "].  [" & AttFn & "]"
Next
End Sub

Private Function Init__AttAy() As Att()
Dim O() As Att
Dim U1%, U2%, U3%, J%, A1$(), A2$(), A3$(), M As Att
Dim Ft1$, Ft2$, Pth$
Pth = PthQue & A_Fdr & "\"
A3 = PthAyFnLik(Pth, "Trip-####-##-##?### att-## (ord-####-##-##?#### content-##).png")
If Sz(A3) = 0 Then Exit Function

Ft1 = Pth & "Att_OfCusCd.txt"
Ft2 = Pth & "Att_OfContentRmk.txt"
A1 = FtAy(Ft1)
A2 = FtAy(Ft2)
U1 = UB(A1)
U2 = UB(A2)
U3 = UB(A3)
If U1 <> U2 Or U2 <> U3 Then WrtLog "Att Error: U1/U2/U3 are diff: " & U1 & "/" & U2 & "/" & U3: Exit Function
ReDim O(U1)
For J = 0 To U1
    M.CusCd = A1(J)
    M.ContentRmk = A2(J)
    M.AttFn = A3(J)
    O(J) = M
Next
Init__AttAy = O
End Function

Property Get AttFnAy() As String()
Dim J%, N%, O$()
N = NAtt
If N = 0 Then Exit Property
ReDim O(N - 1)
For J = 0 To N - 1
    O(J) = A_AttAy(J).AttFn
Next
AttFnAy = O
End Property

Private Property Get NAtt%()
On Error Resume Next
NAtt = UBound(A_AttAy) + 1
End Property

Function FillWs(Wb As Workbook) As Boolean
Wb.Application.ScreenUpdating = False
WsAtt.Copy Wb.Sheets(1)
Dim Ws As Worksheet
Set Ws = Wb.Sheets(1)
Dim J%, ORno%, CusCd$, ContentRmk$, AttFn$, M As Att
ORno = 1
If NAtt = 0 Then
    Ws.Range("A1").Value = "No attachement pages"
Else
    Ws.Range("A:A").ColumnWidth = 10
    Ws.Range("B:B").ColumnWidth = 70
    For J = 0 To NAtt - 1
        M = A_AttAy(J)
        CusCd = M.CusCd
        ContentRmk = M.ContentRmk
        AttFn = M.AttFn
        If rPutOneAtt(Ws, ORno, CusCd, ContentRmk, AttFn) Then FillWs = True
    Next
    '--
    Dim TripNm$
    TripNm = Mid(AttFn, 6, 20 - 6)
    Ws.PageSetup.CenterHeader = "行程 " & TripNm
    Ws.PageSetup.CenterFooter = "附頁 &P 之 &N"
End If
End Function

Function rPutOneAtt(Ws As Worksheet, ByRef ORno%, CusCd$, ContentRmk$, AttFn$) As Boolean
'AttFn fmt = Trip-2015-01-01#001 Att-02 (Ord-2015-01-01#1234 Content-02).png
'            123456789012345678901234567890123456789012345678901234567890123
'                     1         2         3         4         5         6
'Fdr   fmt = C:\xampp\htdocs\loadPlan\pgm\loadSheet\QueTest\trip-2015-01-01#002
Dim Fdr$
Fdr = A_Fdr
If ORno <> 1 Then Ws.HPageBreaks.Add Before:=Ws.Range("A" & ORno)      '<== Set page break

Dim A$, J%

Dim OrdNm$, AttNo%, ContentNo%  '<- AttFn
    A = AttFn
    AttNo = Mid(A, 25, 2)
    OrdNm = Mid(A, 33, 48 - 33)
    ContentNo = Mid(A, 57, 2)

Ws.Range("A" & ORno).Value = "客戶" '<== Put Nm
Ws.Range("B" & ORno).Value = CusCd  '<== Put Nm
ORno = ORno + 1                        '<== ORno++

Ws.Range("A" & ORno).Value = "柯打"  '<== Put Nm
Ws.Range("B" & ORno).Value = OrdNm   '<== Put Nm
ORno = ORno + 1                       '<== ORno++

Ws.Range("A" & ORno).Value = "送貨內容"
Ws.Range("A" & ORno).VerticalAlignment = XlVAlign.xlVAlignCenter
Ws.Range("B" & ORno).WrapText = True
Ws.Range("B" & ORno).Value = "(" & ContentNo & ") " & Replace(ContentRmk, "\n", Chr(10))
Ws.Range("B" & ORno).VerticalAlignment = XlVAlign.xlVAlignCenter

ORno = ORno + 1                       '<== ORno++


Dim Ffn$
    Ffn = PthQue & Fdr & "\" & AttFn

If Not IsFile(Ffn) Then
    WrtLog vbTab & "Order content page not file: [" & Ffn & "]"
    rPutOneAtt = True                                            '<== Return True for err
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



