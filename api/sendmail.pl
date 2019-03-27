#/volume/perl/bin/perl -w
###########################################################
##Author : Nida Naz                                      ##
##Synopsis : For sending mail for pending reviewer       ##
##of the PRs data is hardcoded from 1- Aug -2018         ##
##It is used for tracking the review of https://         ##
##jdiregression.juniper.net/sanitycheck/pr_tracking/ page##
###########################################################
use lib qw(/volume/labtools/lib);
use lib qw(/homes/ravikanth/bin /volume/labtools/lib /volume/labtools/eabu/lib/Dashboard);
use POSIX qw(strftime);
print "Date:$date1\n";
$datestring = strftime "%F", localtime;

use Time::Piece;
use Time::Seconds;

my $format = '%Y-%m-%d';

#$datestring= '2018-07-31';


my $dt1 = Time::Piece->strptime($datestring, $format);
my $dt2 = $dt1 - ONE_DAY;
my $str2 = $dt2->strftime($format);
#print $str2;
my $dt3 = $dt1 + ONE_DAY;
my $str3 = $dt3->strftime($format);
#print $str3;

use Data::Dumper;
use SysTest::DB;
my $params;
use Date::Calc qw(Add_Delta_Days Delta_Days Date_to_Text);
use Mail::Sendmail;
set_dsn('readonly');

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
#my $hostname1 = 'jdi.juniper.net';
my $dsn2 = "dbi:Pg:database=regression_pr_db;host=$hostname;port=$port";
my $dbh2 = DBI->connect($dsn2,$user,$pass) or die "Couldn't connect to database ";

my $query = "select number, state,originator,manager,mentor,reviewer_approval,reviewer_approved,manager_approval,synopsis,jnprmgr,reviewer,orig_com,created from pr_tracking_new where created>'2018-07-31'";
my $sth=$dbh2->prepare($query);
#print "$query";
$sth->execute;
my $jnprmgr="";
my $mentor ="";
my $r=$sth->fetchall_arrayref({});
my $message = "Test Mail";
foreach my $ar(@{$r}){
    $pr_number = $ar->{number};
    $pr_number =~s/^\s+|\s+$//g;
    $state = $ar->{state};
    $state =~s/^\s+|\s+$//g;
    $originator = $ar->{originator};
    $originator =~s/^\s+|\s+$//g;
    $manager = $ar->{manager};
    $manager =~s/^\s+|\s+$//g;
    $mentor = $ar->{mentor};
    $mentor =~s/^\s+|\s+$//g;
    $reviewer_comments = $ar->{reviewer_approval};
    $reviewer_comments =~s/^\s+|\s+$//g;
    $reviewer_approved = $ar->{reviewer_approved};
    $reviewer_approved =~s/^\s+|\s+$//g;
    $manager_approval = $ar->{manager_approval};
    $manager_approval =~s/^\s+|\s+$//g;
    $synopsis = $ar->{synopsis};
    $synopsis =~s/^\s+|\s+$//g;
    $jnprmgr = $ar->{jnprmgr};
    $jnprmgr =~s/^\s+|\s+$//g;
    $reviewer = "$manager";
    $reviewer = $ar->{reviewer};
    $reviewer =~s/^\s+|\s+$//g;
    $orig_com = $ar->{orig_com};
    $orig_com =~s/^\s+|\s+$//g;
    $created = $ar->{created};
    $created =~s/^\s+|\s+$//g;

    if($reviewer_comments =~/^$/){
        if ($mentor =~/^$/){
            if ($jnprmgr=~/^$/){
                $mentor = "$manager";
            }
            else {
                $mentor = "$manager";
            }
        }
        $before = Time::Piece->strptime($created, "%Y-%m-%d");
        $now = localtime;

        $diff = $now - $before;
        #print int($diff->days), " days since $before\n";

        $to = "$mentor\@juniper.net";
        $cc = "nidanaz\@juniper.net,$manager\@juniper.net,$jnprmgr\@juniper.net, $originator\@juniper.net,$reviewer\@juniper.net";

        $message = "<p>Hi</br></br>PR $pr_number raised by $originator is pending for review.</br>kindly review immediately.</br>click <a href=
        \'https://jdiregression.juniper.net/sanitycheck/pr_review/\' target=\'_blank\'>PR Review Page</a>for updating</br></br>Thanks,</br>Review Tool</p> ";
    }
    elsif($reviewer_comments !~/^$/ and $orig_com!~/Yes/i){
        if ($mentor =~/^$/){
            if ($jnprmgr=~/^$/){
                $mentor = "$manager";
            }
            else {
                $mentor = "$manager";
            }
        }

        $to = "$originator\@juniper.net";
        $cc = "nidanaz\@juniper.net,$manager\@juniper.net,$jnprmgr\@juniper.net, $mentor\@juniper.net,$reviewer\@juniper.net";

        $message = "<p>Hi</br></br>PR $pr_number raised by you got comment from  $reviewer </br>comments:</br>$reviewer_comments.</br>kindly incoporate the comments immediately and update the column \"Action taken by originator as \'Yes\'\". click <a href=\'https://jdiregression.juniper.net/sanitycheck/pr_review/\' target=\'_blank\'>PR Review Page</a>for updating.</br></br>Thanks,</br>Review Tool</p>";
    }
    elsif($reviewer_comments !~/^$/ and $orig_com =~/Yes/i and $reviewer_approved !~/Approved/i){
        if ($mentor =~/^$/){
            if ($jnprmgr=~/^$/){
                $mentor = "$manager";
            }
            else {
                $mentor = "$manager";
            }
        }

        $to = "$mentor\@juniper.net";
        $cc = "nidanaz\@juniper.net,$manager\@juniper.net,$jnprmgr\@juniper.net, originator\@juniper.net,$reviewer\@juniper.net";

        $message = "<p>Hi</br></br>PR $pr_number reviewed have be updated by below comments: action taken by originator as YES .</br>kindly check and if it is not fine then approve else give more comment and update the column \"Action taken by originator as \'No\'\". click on <a href=\'https://jdiregression.juniper.net/sanitycheck/pr_review/\' target=\'_blank\'>PR Review Page</a>for updating.</br></br>Thanks,</br>Review Tool</p> ";
    }

    elsif($reviewer_comments !~/^$/ and $orig_com =~/Yes/i and $reviewer_approved =~/Approved/i and $manager_approval !~'Approved'){
        if ($mentor =~/^$/){
            if ($jnprmgr=~/^$/){
                $mentor = "$manager";
            }
            else {
                $mentor = "$jnprmgr";
            }
        }

        $to = "$jnprmgr\@juniper.net";
        $cc = "nidanaz\@juniper.net,$mentor\@juniper.net,$manager\@juniper.net, $originator\@juniper.net,$reviewer\@juniper.net";

        $message = "<p>Hi</br></br> PR $pr_number is waiting for manager approval as this is approved by the reveiwer.</br> click on <a href=\'https://jdiregression.juniper.net/sanitycheck/pr_review/\' target=\'_blank\'>PR Review Page</a>for updating.</br><br>Thanks,</br>Review Tool</p> ";
    }
    my $sub= "";
    if (int($diff->days) > 2){

    $sub = "Escalation!!! as review is pending for int($diff->days) days ";

    }

my $to1 = "nidanaz\@juniper.net,";
my $cc1 = "nidanaz\@juniper.net,";
open(MAIL, "|/usr/sbin/sendmail -t");
print MAIL <<EOM
Mime-Version: 1.0
Content-type: text/html; charset="iso-8859-1"
From:PR_Review
To:$to
cc:$cc
Subject:$sub \:Review for $pr_number
<html>
<body>
$message
</body>
</html>
EOM

}