#y /volume/perl/bin/perl -w
use lib qw(/volume/labtools/lib);
use lib qw(/homes/ravikanth/bin /volume/labtools/lib /volume/labtools/eabu/lib/Dashboard);
use POSIX qw(strftime);
#print "Date:$date1\n";
$datestring = strftime "%F", localtime;

use Time::Piece;
use Time::Seconds;

my $format = '%Y-%m-%d';

#$datestring= '2018-07-31';


my $dt1 = Time::Piece->strptime($datestring, $format);
my $dt2 = $dt1 - ONE_DAY;
my $str2 = $dt2->strftime($format);
print $str2;
my $dt3 = $dt1 + ONE_DAY;
my $str3 = $dt3->strftime($format);
print $str3;

use Data::Dumper;
use SysTest::DB;
my $params;
use Date::Calc qw(Add_Delta_Days Delta_Days Date_to_Text);
use Mail::Sendmail;
set_dsn('readonly');
my $stmnt=0;
my $rv=0;
my $query2 = "";
 my $query4 ="";
#----------DB SystestLive-----------#
#my $dbh = DBI->connect("dbi:Pg:dbname='systest_live';host='tt-db.juniper.net';port=6553;", 'readonly', 'readonly', { pg_server_prepare => 0}) || die("Unable to connect to DB:" . $DBI::errstr);
#----------DB Regressionr---------------#
my $hostname = 'eabu-systest-db';
my $user = "postgres";
my $pass = "postgres";
my $port = "5432";
my $dsn1 = "dbi:Pg:database=dashboard;host=$hostname;port=$port";
my $dbh = DBI->connect($dsn1,$user,$pass) or die "Couldn't connect to database ";
#-----------------------------------------#
my $hostname1 = 'rbu.juniper.net';
my $dsn2 = "dbi:Pg:database=regression_pr_db;host=$hostname1;port=$port";
my $dbh2 = DBI->connect($dsn2,$user,$pass) or die "Couldn't connect to database ";


my $query = "select report from heirarchy where heirarchy~'rpathak'";
my $sth=$dbh->prepare($query);
$sth->execute;
my $r=$sth->fetchall_arrayref({});
foreach my $ar(@{$r}){
    $report = $ar->{report};
    $report =~s/^\s+|\s+$//g;
    if ($report!~/^$/){
        push (@query,"Originator==\"$report\"");
    }
}


$query_originator = join("\|",@query);
@dat=();
#print "query-pr --expr \'($query_originator) & (Created >\"$str2\") & (Created < "$str3")\'".'  --format \'"%s::&&::%s::&&::%s::&&::%s::&&::%s::&&::%s::&&::%Q::&&::%s::&&::%s::&&::%s::&&::%s::&&::%s::&&::%s::&&::%s" Number State Resolution Planned-Release Category Resolution-Reason Created Originator Attributes Last-Known-Working-Release Symptom Problem-Level Found-During Synopsis\''."\n\n";
#my @dat = `query-pr --expr ' ($query_originator)  & (Created ~ "$datestring") '  --format '"%s::&&::%s::&&::%s::&&::%s::&&::%s::&&::%s::&&::%Q::&&::%s::&&::%s::&&::%s::&&::%s::&&::%s::&&::%s::&&::%s"  Number State Resolution Planned-Release Category Resolution-Reason Created Originator Attributes Last-Known-Working-Release Symptom Problem-Level Found-During Synopsis'`;

my @dat = `query-pr --expr ' ($query_originator)  &  (Created > "$str2")  &  (Created < "$str3") & (Functional-Area == "testscript")' --format '"%s::&&::%s::&&::%s::&&::%s::&&::%s::&&::%s::&&::%Q::&&::%s::&&::%s::&&::%s::&&::%s::&&::%s::&&::%s::&&::%s"  Number State Resolution Planned-Release Category Resolution-Reason Created Originator Attributes Last-Known-Working-Release Symptom Problem-Level Found-During Synopsis'`;

#my @dat = `query-pr --expr ' ($query_originator)  &  (Created > "2018-07-01")  &  (Created < "2018-08-10")' --format '"%s::&&::%s::&&::%s::&&::%s::&&::%s::&&::%s::&&::%Q::&&::%s::&&::%s::&&::%s::&&::%s::&&::%s::&&::%s::&&::%s"  Number State Resolution Planned-Release Category Resolution-Reason Created Originator Attributes Last-Known-Working-Release Symptom Problem-Level Found-During Synopsis'`;


my $manager;
my $lgth = @dat;
if ($lgth) {
    foreach (@dat) {
        chomp $_;
        my @tmp = split("::&&::",$_);
        $state = "$tmp[1]";
        $planned="$tmp[3]";
        $cate="$tmp[4]";
        $cate=~s/^\s+|\s+$//g;
        $prscope="$tmp[0]";
        $prscope=~s/^\s+|\s+$//g;
        #$prscope=~s/\-/s/g;
        print "prscope trimmed : $prscope : $tmp[0]\n";
        ($scope) = $prscope=~/.*\-(\d+)/;
        $resolutionres="$tmp[5]";
        $created = $tmp[6];
        my ($date) = $created =~ /(.*)?\s+\d+/;
        $originator = "$tmp[7]";
        $attribute = "$tmp[8]"; 
        $last_known = "$tmp[9]";
        $symptom ="$tmp[10]";
        $problem_level ="$tmp[11]";
        $found_during ="$tmp[12]";
        $synopsis = "$tmp[13]";
	$synopsis  =~ s/[#\-%&\$*+()\.']/ /g;
     
        my $query = "select heirarchy from heirarchy where report ='$originator'";
        my $sth=$dbh->prepare($query);
        $sth->execute;
        my $r=$sth->fetchall_arrayref({});
        foreach my $ar(@{$r}){
            $heirarchy = $ar->{heirarchy};
            $heirarchy =~s/^\s+|\s+$//g;
            @heir = split(',',$heirarchy);
            $manager=$heir[0];
        }


my $query = "select distinct mentor,jnprmgr,team from pr_reviewer  where tcsuser='$originator'";
my $sth=$dbh2->prepare($query);
#print "$query";
$sth->execute;
my $rmanager="";
my $mentor ="";
my $r=$sth->fetchall_arrayref({});
foreach my $ar(@{$r}){
    $mentor = $ar->{mentor};
    $mentor =~s/^\s+|\s+$//g;
    $rmanager = $ar->{jnprmgr};
    $rmanager =~s/^\s+|\s+$//g;
    $team = $ar->{team};
    $team =~s/^\s+|\s+$//g;

}

if ($rmanager =~/^$/){
        $rmanager = $manager;
        }


   print "$tmp[0],$tmp[1],$tmp[2],$planned,$cate,$resolutionres,$date,$manager,$originator\n";

         $query2 = "update pr_tracking_new set state='$tmp[1]', category = '$cate',attribute='$attribute', resolution_reason='$resolutionres',manager='$manager',jnprmgr='$rmanager', mentor='$mentor', scope ='$scope', team='$team' where number='$tmp[0]' returning number";
         $stmnt = $dbh2->do($query2)or die $DBI::errstr;

        if ($stmnt == 0){
print "not updated \n";

             $query4 = "insert into pr_tracking_new (number,state,resolution,planned_release,category,resolution_reason,created,manager,originator,scope,heirarchy,attribute,last_known,symptom,problem_level,found_during,synopsis,jnprmgr,mentor,team) values('$tmp[0]','$tmp[1]','$tmp[2]','$planned','$cate','$resolutionres','$date','$manager','$originator','$scope','$heirarchy','$attribute','$last_known','$symptom','$problem_level','$found_during','$synopsis','$rmanager','$mentor','$team')";
            my $dqh_insert = $dbh2->prepare($query4);
            $rv = $dqh_insert->execute();
print "$rv insert value\n";
        }
    }  
}


##sendMail($date1,$datestring);


my $to1 = "nidanaz\@juniper.net";
my $cc = "nidanaz\@juniper.net";
open(MAIL, "|/usr/sbin/sendmail -t");
print MAIL <<EOM
Mime-Version: 1.0
Content-type: text/html; charset="iso-8859-1"
From:CRON
To:$to1
cc:$cc
Subject:PR Query Ran for $datestring
<html>
<body>
Hi,
This is to inform that prquery update tool for PR Tracking page cron is working fine.
PR Query Ran for $datestring $date1
update query result = $stmnt $query4 </br>
for insert statement = $rv $query2</br>
</body>
</html>
EOM


