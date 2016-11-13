<?php

namespace App\Bundle\CoreBundle\Command;

use App\Bundle\CoreBundle\Entity\User;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Helper\FormatterHelper;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoder;

class CreateSuperAdminCommand extends ContainerAwareCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('app:super_admin:create')
            ->setDescription('Creating Super Admin')
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /** @var QuestionHelper $dialog */
        $dialog = $this->getHelperSet()->get('question');

        /** @var FormatterHelper $formatter */
        $formatter = $this->getHelper('formatter');

        $output->writeln("");

        $emailQuestion = new Question(" Enter email [admin@example.com]: ", "admin@example.com");
        $email = $dialog->ask($input, $output, $emailQuestion);

        $firstnameQuestion = new Question(" Enter first name [Demo]: ", "Demo");
        $firstname = $dialog->ask($input, $output, $firstnameQuestion);

        $lastnameQuestion = new Question(" Enter last name [Demos]: ", "Demos");
        $lastname = $dialog->ask($input, $output, $lastnameQuestion);

        $passwordQuestion = new Question(" Enter password: ", false);
        $passwordQuestion->setHidden(true)->setHiddenFallback(false);
        $password = $dialog->ask($input, $output, $passwordQuestion);

        if (!$password) {
            $output->writeln("");
            $errorMessages = ['Error!', 'Please enter not blank password!'];
            $formattedBlock = $formatter->formatBlock($errorMessages, 'error');
            $output->writeln($formattedBlock);
            $password = $dialog->ask($input, $output, $passwordQuestion);
            $output->writeln("");
        }

        if (!$password) {
            $output->writeln("");
            $errorMessages = ['Error!', 'If password is blank then goodbye!'];
            $formattedBlock = $formatter->formatBlock($errorMessages, 'error');
            $output->writeln($formattedBlock);
            $output->writeln("");
            return;
        }

        $output->writeln("");
        $output->writeln('<info>  Summary information:</>');

        $table = new Table($output);
        $table
            ->setHeaders(['Email', 'First Name', 'Last Name'])
            ->setRows([
                [$email, $firstname, $lastname]
            ])
        ;
        $table->render();

        $output->writeln("");
        $question = new ConfirmationQuestion("it's ok? [y/N]: ", false);

        if (!$dialog->ask($input, $output, $question)) {
            $errorMessages = ['Goodbye!'];
            $formattedBlock = $formatter->formatBlock($errorMessages, 'error');
            $output->writeln($formattedBlock);
            return;
        }

        /** @var EntityManager $em */
        $em = $this->getContainer()->get('doctrine')->getManager();

        /** @var UserPasswordEncoder $encoder */
        $encoder = $this->getContainer()->get('security.password_encoder');

        if ($em->getRepository('AppCoreBundle:User')->findBy(['email' => $email])) {
            $errorMessages = ['User are exist with input email!'];
            $formattedBlock = $formatter->formatBlock($errorMessages, 'error');
            $output->writeln($formattedBlock);
            return;
        }

        $admin = new User();
        $admin->setEmail($email);
        $admin->setFirstname($firstname);
        $admin->setLastname($lastname);
        $admin->setPassword($encoder->encodePassword($admin, $password));
        $admin->setRoles(['ROLE_SUPER_ADMIN']);
        $em->persist($admin);
        $em->flush();

        $output->writeln('<bg=green>Well done! Admin was created.</>');
    }
}
