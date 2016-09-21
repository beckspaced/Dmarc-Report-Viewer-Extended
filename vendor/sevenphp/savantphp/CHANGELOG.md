## CHANGELOG

### v3.0.0 (14 Feb 2016)

[MAJOR]
+ renamed class Savant to SavantPHP
+ renamed class SavantError to SavantPHPerror
+ renamed class SavantException to SavantPHPexception
+ added possibility of injecting a Container object inside Savant & hence added the property $this->container
+ renamed the method template() to getPathToTemplate()
+ added the method includeTemplate() which is dependent on method getPathToTemplate()
+ renamed the config KEY 'templat_path' to 'template_path_list'
+ renamed the config KEY 'fetch' to 'fetched_tpl'
+ Introduced class constants to enumerate config KEYs - better like this
- removed support for resources & compilation of template
- removed the method assign() - let's enforce using by direct property usage
- removed the method assignRef() - let's enforce using by direct property usage
- removed the escape() functionality | Reason : Sanitization should be the responsibility of another component. Savant will handle ONLY the responsibility of being a MINIMALIST TEMPLATE
- removed eprint() as well as per above

[MINOR]
+ Replaced all occurrence of array() into the new [] syntax in all files

NOTE:
This commit will not be backward compatible, let's tag at v3 now


### v2.0.0 (25 Dec 2015)

- removed support for Filters & Plugins


### v1.0.0 (22 Dec 2015)

* initial commit

[MAJOR]
+ added namespace support
+ renamed class Savant3 to Savant
+ renamed class Error to SavantError
+ renamed class Exception to SavantException
- deleted the folder resources and Savant3

NOTE:
This commit will not be backward compatible with the old php savant.

