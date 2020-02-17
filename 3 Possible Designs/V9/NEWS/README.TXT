FusionPHP - Fusion News 3.6.1  
_____________________________     
http://www.fusionphp.net
Copyright © 2003 FusionPHP   
 
 
!!!Bringing a new dimension to News Management!!!          
                                                                       
Summary of this readme file:
-----------------------------                                        
 1. Introduction                                                     
 2. Disclaimer                                                       
 3. Support                                                          
 4. How to install                                                   
 5A. How to Upgrade from v3.51 to v3.6.1
 5B. How to Upgrade from v3.6 to v3.6.1
 6. How to get the news on your site.                                
                                                                       
______________________________________________________________________
                                                                       
     1. Introduction:                                                    
     ================                                                    
                                                                       
     Thanks for downloading this wonderful script.                      
     I hope this script has everything you need :) 

     This script should be downloaded from www.fusionphp.net
     since we do not allow mirroring of the script for download.                           
                                                                      
                                                                       
    
     2a. Disclaimer:                                                      
     ==============                                                      
                                                                       
     Use of this script is TOTTALY at your own risk.                                                                                                                                  
     2b. LICENSE:                                                      
     ==============                                                      
                                                                       
     This program is free software; you can redistribute it and/or 
     modify it under the terms of the GNU General Public License as
     published by the Free Software Foundation; either version 2 of 
     the License, or (at your option) any later version.

     This program is distributed in the hope that it will be useful, 
     but WITHOUT ANY WARRANTY; without even the implied warranty of 
     MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the 
     GNU General Public License for more details.

     You should have received a copy of the GNU General Public License 
     along with this program; if not, write to the Free Software 
     Foundation, Inc., 59 Temple Place, Suite 330, Boston, 
     MA 02111-1307 USA

     ALSO
     
     You cannot sell part, or the whole, of it's code.
     You cannot claim part or the whole of it's code to be yours. 
     The copyright notice in the admin panel must stay instact.
     Further more Fusion News starting from 3.6 is now released 
     under de GNU GPL license, please read the license.txt file 
     included in the zip root. You MUST agree do this license and 
     the GNU GPL license to use this script in any way.

     3. Support:                                                         
     ===========                                                         
                                                                       
     FREE Support is always, and for everybody available:                
     - Our forums: http://www.fusionphp.com/forums                       
     - Our support email : support@fusionphp.net                           
     - Our FAQ: http://faq.fusionphp.net                                 
     - Our Online Manual: http://manual.fusionphp.net                    
                                                                       
                                                                       
                                                                       
   
     4. How to install:                                                  
     ==================                                                  
                                                                       
     1. First open your ftp program, login to your account.              
                                                                        
     2. Now make a new folder, call it News or whatever                  
        you want to call it.                                            
                                                                          
     3. CHMOD that folder to 777. (if you dont know how to chmod
        please visit our faq system.) "http://www.fusionphp.net/faq/"                                         
                                                                       
     4. Upload ALL of the files that are located in the upload folder,
        to the folder that you created.
 
      
     5. Once all of the folders and files are uploaded, u can chmod all files and folders 
        to 777 for convinience or use the appropriate chmods described in the installation.  
                                                                       
                                                                       
        Now run the installer.  GOTO:                                        
        "http://www.yourdomain.com/subfolder/installer.php".        
                                                                     
        Follow the steps on the installer, and you can have fusion news     
        Running in no time :)                                               
                                                                       
                                                                       
        *NOTE: When you run the installer                                   
               Leave the path as ./ on the Path field, and that should work

     6. Delete the installer.php file aswell as the db_convertor.php file for 
        security reasons.  
                                                                       
                                                                       
                                                                       
                                                                       
     
     5A.  How to Upgrade from v3.51 to 3.6.1
     =======================================
     
     1.  Backup your Fusion News 3.51 folder.

     2.  Then Install fusion news 3.6.1.
         Follow the intructions above.

     3.  The next step download the news.db and comments.db.php files from your 
         fusion news 3.51 folder.

     4.  Now upload those files to the folder where you have the 3.6 files.

     5.  The final step is to run the db_convertor.php file that was 
         included witht the 3.6.1 files.

         Example: http://www.yoursite.com/subfolder/db_convertor.php

         Once you run the converter, it will convert your news and comments,
         and after it is done, you can go back to the index.php :)


     5B.  How to Upgrade from v3.6 to 3.6.1
     ======================================
     
     1.  Backup your Fusion News 3.6 folder.

     2.  Then Install fusion news 3.6.1.
         Follow the intructions above.

     3.  Put smillies.db, users.php and your full news subfolder in the new 
         3.6.1 folder, overwriting the default files.


     6. How to get the news on your site.                               
     ===================================                               
                                                                       
        You have two options.  You can use the PHP or SSI include code.   
                                                                       
                                                                       
        PHP include code:                                                   
                                                                       
        Insert this code where you want the news to appear.     
        it can be in your index or main....etc                                								
      
      
        <?php include "news/news.php";?>  Change news if you need to     
                                                                       
        Now, make sure that the file where you inserted the           
        include code has a .php extension                            
                                                                       
        Example:  If you inserted the code in your index.html you need   
               Rename it to index.php                               
                                                                        
  -------------------------------------------------------------------  
                                                                        
        SSI include code:                                             
                                                                       
        You can also use the SSI include code to make the news          
        Show up on your website.                                     
                                                                       
        Insert this code where you want the news to appear.           
                                                                                  
        <!--#include virtual="news/news.php" -->                       
                                                                       
                                                                       
        as with the PHP include code.  YOu need to rename the file    
        where you insert the include code to .shtml                    
                                                                       
        Example:  if you inserted the code in your index.html file, you need 
        to rename your index.html file to index.shtml                    
                                                                       
  -------------------------------------------------------------------- 
                                                                        
                                                                       
      More Help? :)  if you need addition help understanding this      
      Readme file.  you can e-mail me.  reoplex@fusionphp.net          
                                                                       
                                                                       
      ENJOY FUSION NEWS 3.6.1                                         
                                                                       
      UPDATE 05/03/2003                                                   
                                                                       											
                                                                       
***********************************************************************
Created on September 1st                   http://www.fusionphp.net  
by Pattattenboerken                                                  
                                                                     
Updated by Dannonb (reoplex)