<?php /** @var \Entity\User $user */ ?>
<div class="row">
    <h3>User Profile</h3>
    <table>
        <tr>
            <td>Username:</td>
            <td><?php echo htmlspecialchars($user->getUsername()); ?></td>
            <td rowspan="7" colspan="3" width="500">
                <div align="right">
                    <img height="300" width="300" src="<?php echo $user->getImageUrl(); ?>" alt="#" class="avatar">
                </div>
            </td>
        </tr>
        <tr><td>Name:</td><td><?php echo htmlspecialchars($user->getFname()); ?> <?php echo htmlspecialchars($user->getLname()); ?></td></tr>
        <tr><td>Email:</td><td><?php echo $user->getEmail(); ?></td></tr>
        <tr><td>State:</td><td><?php echo htmlspecialchars($user->getStateName()); ?></td></tr>
        <tr><td>City:</td><td><?php echo htmlspecialchars($user->getCityName()); ?></td></tr>
        <tr><td>Joined Date:</td><td><?php echo date("n/j/Y", strtotime($user->getJoined())); ?></td></tr>
        <tr><td>Capabilities:</td><td><?php echo $user->getCapabilities(); ?></td></tr>
        <tr><td>Address1:</td><td><?php echo htmlspecialchars($user->getAddress1()); ?></td></tr>
        <tr><td>Address2:</td><td><?php echo htmlspecialchars($user->getAddress2()); ?></td></tr>
        <tr><td>Zip code:</td><td><?php echo htmlspecialchars($user->getZipCode()); ?></td></tr>
        <tr><td>Phone:</td><td><?php echo htmlspecialchars($user->getPhone()); ?></td></tr>
        <tr><td>Instant Donation:</td><td><?php echo $user->isInstantDonationsEnabled()?'Yes':'No'; ?></td></tr>
        <tr><td>Hide Donation:</td><td><?php echo $user->getHideUnhideDonation()?'Yes':'No'; ?></td></tr>
        <tr><td>Hide Badges:</td><td><?php echo $user->getHideUnhideBadges()?'Yes':'No'; ?></td></tr>
    </table>
</div>