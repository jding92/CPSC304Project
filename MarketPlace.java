// We need to import the java.sql package to use JDBC

import java.io.BufferedReader;
import java.io.IOException;
import java.sql.Connection;
import java.sql.DriverManager;
import java.sql.PreparedStatement;
import java.sql.SQLException;

public class MarketPlace {
    private Connection con;
    private BufferedReader in;

    // TODO: dunno how to link to frontend to get user/pass yet
    private boolean connect(String username, String password) {
        String connectURL = "jdbc:oracle:thin:@dbhost.ugrad.cs.ubc.ca:1522:ug"; 

        try {
	        con = DriverManager.getConnection(connectURL,username,password);
	        System.out.println("\nConnected to Oracle!");
            return true;
        } catch (SQLException ex) {
            System.out.println("Message: " + ex.getMessage());
	        return false;
        } 
    }

    // copied from branch.java so doesn't work yet but use as template
    private void insertBranch()
    {
        int                bid;
        String             bname;
        String             baddr;
        String             bcity;
        int                bphone;
        PreparedStatement  ps;

        try
        {
            ps = con.prepareStatement("INSERT INTO branch VALUES (?,?,?,?,?)");

            System.out.print("\nBranch ID: ");
            bid = Integer.parseInt(in.readLine());
            ps.setInt(1, bid);

            System.out.print("\nBranch Name: ");
            bname = in.readLine();
            ps.setString(2, bname);

            System.out.print("\nBranch Address: ");
            baddr = in.readLine();

            if (baddr.length() == 0)
            {
                ps.setString(3, null);
            }
            else
            {
                ps.setString(3, baddr);
            }

            System.out.print("\nBranch City: ");
            bcity = in.readLine();
            ps.setString(4, bcity);

            System.out.print("\nBranch Phone: ");
            String phoneTemp = in.readLine();
            if (phoneTemp.length() == 0)
            {
                ps.setNull(5, java.sql.Types.INTEGER);
            }
            else
            {
                bphone = Integer.parseInt(phoneTemp);
                ps.setInt(5, bphone);
            }

            ps.executeUpdate();

            // commit work
            con.commit();

            ps.close();
        }
        catch (IOException e)
        {
            System.out.println("IOException!");
        }
        catch (SQLException ex)
        {
            System.out.println("Message: " + ex.getMessage());
            try
            {
                // undo the insert
                con.rollback();
            }
            catch (SQLException ex2)
            {
                System.out.println("Message: " + ex2.getMessage());
                System.exit(-1);
            }
        }
    }
}