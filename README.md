# Lifter – A PHP tool to automate your update processes

Lifter is a powerful tool designed to automate software updates for PHP projects. 
Leveraging the capabilities of tools like Composer and Rector, 
Lifter simplifies the process of updating dependencies and migrating code to newer versions. 

Whether you're a developer managing multiple projects or part of a team working on a large codebase, 
Lifter streamlines the update process, saving time and reducing the risk of errors.

## Features

* **Automated Dependency Updates**: Lifter automates the process of updating dependencies specified in your composer.json file, ensuring that your project stays up-to-date with the latest versions.
* **Code Migration with Rector**: Integrating with Rector, Lifter facilitates smooth migration of code when updating to newer versions of libraries or frameworks. Rector automates code refactoring, making it easier to adopt best practices and utilize new features.
* **Customizable Configuration**: Lifter offers flexible configuration options, allowing you to tailor the update process to fit your project's specific requirements. Customize which dependencies to update, configure Rector rules, and more.

## Getting Started

Follow these steps to start using Lifter in your PHP project:

1. **Installation:** Install Lifter via Composer by running the following command:

   ```bash
   $ composer require a9f/lifter
   ```

2. **Configuration:** Create a lifter.php configuration file in the root of your project. Here's a basic example:

   ```php
    return static function (\a9f\Lifter\Configuration\LifterConfig $config) {
        $config->withWorkingDirectory(__DIR__)
            ->withRectorBinary('vendor/bin/rector')
            ->withRectorConfigFile(__DIR__ . '/rector.php');
    
        $config->withSteps([
            new \a9f\Lifter\Upgrade\Step\ShellStep(
                'Add Hello World',
                <<<SCRIPT
    DATE=$(date +%s)
    echo "Hello World" | tee \$DATE.txt
    git add \$DATE.txt
    SCRIPT
            ),
            new \a9f\Lifter\Upgrade\Step\RectorStep(
                'Apply PHP 8.3 set list',
                static function (\Rector\Config\RectorConfig $rectorConfig) {
                    $rectorConfig->sets([
                        \Rector\Set\ValueObject\LevelSetList::UP_TO_PHP_83,
                    ]);
                }
            )
        ]);
    };
   ```

3. **Usage:** Run Lifter from the command line in your project directory:
   
    ```bash
    $ vendor/bin/lifter run -f lifter.php
    ```

    This command will apply the steps you specified in your `lifter.php` in the order in which they appear in the file.

    Each step will lead to a separate Git commit.
    By default, Lifter will prefix the commit message with `(lifter)` to make its work easier to spot in the history.

    To change this prefix, call `$config->withCommitMessagePrefix('my prefix:');`. To get rid of the prefix, pass an empty string.

## Contributing

If you encounter any issues or have suggestions for improvements, 
we welcome contributions from the community. Here's how you can contribute:

1. Fork the repository. 
2. Make your changes.
3. Submit a pull request with a clear description of your changes and why they are needed.

## Support

For any questions or support regarding Lifter, please open an issue on GitHub. We'll do our best to assist you promptly.

## License

Lifter is licensed under the MIT License.

## Acknowledgments

Lifter wouldn't be possible without the amazing work of the Composer and Rector teams. 
We're grateful for their contributions to the PHP ecosystem.

-----

Thank you for using Lifter to streamline your software update process! 
We hope it helps make your development workflow more efficient and enjoyable. 
If you have any feedback or suggestions, we'd love to hear from you. 

Happy coding! 🚀