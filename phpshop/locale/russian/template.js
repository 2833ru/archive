// Locale
var locale = {
	charset: "windows-1251",
    commentList: {
        mesHtml: "������� ���������� ����������� �������� ������ ��� �������������� �������������.\n<a href='/users/?from=true'>������������� ��� �������� �����������</a>.",
        mesSimple: "������� ���������� ����������� �������� ������ ��� �������������� �������������.\n������������� ��� �������� �����������.",
        mes: "��� ����������� ����� �������� ������ ������������� ������ ����� ����������� ���������..."
    },
    OrderChekJq: {
        badReqEmail: "����������, ������� ���������� E-mail",
        badReqName: "�������� ��������,\n��� ������ �������� �� ����� ��� �� 3 ����",
        badReq: "�������� ��������,\n���� ����, ������������ ��� ����������",
        badDelivery: "����������,\n�������� ��������"
    },
    commentAuthErrMess: "�������� ����������� ����� ������ �������������� ������������.\n<a href='" + ROOT_PATH + "/users/?from=true'>����������, ������������� ��� �������� �����������</a>.",
    incart: "� �������",
    cookie_message: "� ����� �������������� �������� ������������ ������������ �� ������ ����� ������������ cookie-�����. ��������� ������ ����, �� ����� ���� �������� �� ������������� ���� cookie-������.",
    show: "��������",
    hide: "������",
    close: "�������",
    FlipClock: {
        years:"���",
        months:"�������",
        days:"����",
        hours:"�����",
        minutes:"�����",
        seconds:"������"
    }
};

$().ready(function () {
    locale_def = locale;
});